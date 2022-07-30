<?php

namespace Jundayw\LaravelOAuth\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface HasOAuthTokensContract
{
    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return MorphTo
     */
    public function tokenable(): MorphTo;

    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return HasOAuthTokensContract|null
     */
    public static function findOAuthToken(string $token): ?HasOAuthTokensContract;
}
