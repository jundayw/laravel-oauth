<?php

namespace Jundayw\LaravelOAuth;

use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;
use Jundayw\LaravelOAuth\Exceptions\AccessTokenExpiredException;
use Jundayw\LaravelOAuth\Exceptions\InvalidAccessTokenException;

trait HasOAuthTokens
{
    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return HasOAuthTokensContract|null
     */
    public static function findAccessToken(string $token): ?HasOAuthTokensContract
    {
        if (($plaintext = Token::decrypt($token)) == false) {
            throw new InvalidAccessTokenException();
        }

        $token = static::where('access_token', $plaintext->get('access_token'))->first();

        if (is_null($token)) {
            throw new InvalidAccessTokenException();
        }

        if (now() > $token->getOriginal('access_token_expire_at')) {
            throw new AccessTokenExpiredException();
        }

        return $token;
    }

    /**
     * Determine if the token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function can(string $scope): bool
    {
        return in_array('*', $this->getAttribute('scopes')) ||
            array_key_exists($scope, array_flip($this->getAttribute('scopes')));
    }

    /**
     * Determine if the token is missing a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function cant(string $scope): bool
    {
        return !$this->can($scope);
    }

}
