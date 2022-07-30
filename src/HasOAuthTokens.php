<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;
use Jundayw\LaravelOAuth\Exceptions\AccessTokenExpiredException;
use Jundayw\LaravelOAuth\Exceptions\InvalidAccessTokenException;

trait HasOAuthTokens
{
    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return MorphTo
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo('tokenable');
    }

    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return HasOAuthTokensContract|null
     */
    public static function findOAuthToken(string $token): ?HasOAuthTokensContract
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

}
