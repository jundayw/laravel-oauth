<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;
use Jundayw\LaravelOAuth\Events\TokenAuthenticated;

class Guard
{
    /**
     * The authentication factory implementation.
     *
     * @var Factory
     */
    protected $auth;

    /**
     * The provider name.
     *
     * @var string
     */
    protected $provider;

    public function __construct(Factory $auth, string $provider = null)
    {
        $this->auth     = $auth;
        $this->provider = $provider;
    }

    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param Request $request
     * @param UserProvider|null $provider
     * @return HasAccessTokensContract|null
     */
    public function __invoke(Request $request, UserProvider $provider = null): ?HasAccessTokensContract
    {
        $token = $this->getTokenFromRequest($request);

        if (!$token) {
            return null;
        }

        $model = OAuth::$oAuthAccessTokenModel;

        $accessToken = $model::findOAuthToken($token);

        if (!$this->isValidAccessToken($accessToken) || !$this->supportsTokens($accessToken->tokenable)) {
            return null;
        }

        $tokenable = $accessToken->tokenable->withAccessToken(
            $accessToken
        );

        event(new TokenAuthenticated($accessToken));

        $fill = [
            'last_used_at' => now()->toDateTimeString(),
        ];

        if (method_exists($accessToken->getConnection(), 'hasModifiedRecords') &&
            method_exists($accessToken->getConnection(), 'setRecordModificationState')) {
            tap($accessToken->getConnection()->hasModifiedRecords(), function($hasModifiedRecords) use ($accessToken, $fill) {
                $accessToken->forceFill($fill)->save();
                $accessToken->getConnection()->setRecordModificationState($hasModifiedRecords);
            });
        } else {
            $accessToken->forceFill($fill)->save();
        }

        return $tokenable;
    }

    /**
     * Get the token from the request.
     *
     * @param Request $request
     * @return string|null
     */
    protected function getTokenFromRequest(Request $request): ?string
    {
        if (is_callable(OAuth::$accessTokenRetrievalCallback)) {
            return (OAuth::$accessTokenRetrievalCallback)($request);
        }

        return $request->bearerToken();
    }

    /**
     * Determine if the provided access token is valid.
     *
     * @param HasOAuthTokensContract|null $accessToken
     * @return bool
     */
    protected function isValidAccessToken(?HasOAuthTokensContract $accessToken): bool
    {
        if (!$accessToken) {
            return false;
        }

        $isValid = $this->hasValidProvider($accessToken->tokenable);

        if (is_callable(OAuth::$accessTokenAuthenticationCallback)) {
            $isValid = (OAuth::$accessTokenAuthenticationCallback)($accessToken, $isValid);
        }

        return $isValid;
    }

    /**
     * Determine if the tokenable model matches the provider's model type.
     *
     * @param HasAccessTokensContract|null $tokenable
     * @return bool
     */
    protected function hasValidProvider(?HasAccessTokensContract $tokenable): bool
    {
        if (is_null($this->provider)) {
            return true;
        }

        $model = config("auth.providers.{$this->provider}.model");

        return $tokenable instanceof $model;
    }

    /**
     * Determine if the tokenable model supports API tokens.
     *
     * @param HasAccessTokensContract|null $tokenable
     * @return bool
     */
    protected function supportsTokens(HasAccessTokensContract $tokenable = null): bool
    {
        return $tokenable && in_array(HasAccessTokens::class, class_uses_recursive(get_class($tokenable)));
    }
}
