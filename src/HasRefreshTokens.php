<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jundayw\LaravelOAuth\Exceptions\InvalidRefreshTokenException;
use Jundayw\LaravelOAuth\Exceptions\RefreshTokenExpiredException;

trait HasRefreshTokens
{
    /**
     * Refresh a new access token for the user.
     *
     * @param Request $request
     * @return Token
     */
    public static function refreshToken(Request $request): Token
    {
        if (is_callable(OAuth::$accessTokenRetrievalCallback)) {
            $token = with(OAuth::$accessTokenRetrievalCallback, function ($accessTokenRetrievalCallback) use ($request) {
                return $accessTokenRetrievalCallback($request);
            });
        } else {
            $token = $request->bearerToken();
        }

        if (!$token) {
            throw new InvalidRefreshTokenException();
        }

        if (($plaintext = Token::decrypt($token)) == false) {
            throw new InvalidRefreshTokenException();
        }

        $refreshToken = static::where('refresh_token', $plaintext->get('refresh_token'))->first();

        if (is_null($refreshToken)) {
            throw new InvalidRefreshTokenException();
        }

        if (now() > $refreshToken->getOriginal('refresh_token_expire_at')) {
            throw new RefreshTokenExpiredException();
        }

        $fill = [
            'access_token' => hash(config('oauth.hash'), $plainTextAccessToken = Str::random(40)),
            'refresh_token' => hash(config('oauth.hash'), $plainTextRefreshToken = Str::random(40)),
            'access_token_expire_at' => now()->addSeconds(config('oauth.access_token_expire_in', 2 * 3600))->toDateTimeString(),
            'refresh_token_expire_at' => now()->addSeconds(config('oauth.refresh_token_expire_in', 24 * 3600))->toDateTimeString(),
        ];

        if (method_exists($refreshToken->getConnection(), 'hasModifiedRecords') &&
            method_exists($refreshToken->getConnection(), 'setRecordModificationState')) {
            tap($refreshToken->getConnection()->hasModifiedRecords(), function ($hasModifiedRecords) use ($refreshToken, $fill) {
                $refreshToken->forceFill($fill)->save();
                $refreshToken->getConnection()->setRecordModificationState($hasModifiedRecords);
            });
        } else {
            $refreshToken->forceFill($fill)->save();
        }

        return new Token($refreshToken, $plainTextAccessToken, $plainTextRefreshToken);
    }

}
