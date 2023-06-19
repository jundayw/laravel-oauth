<?php

namespace Jundayw\LaravelOAuth\Contracts;

use Jundayw\LaravelOAuth\Token;

interface HasRefreshTokensContract
{
    /**
     * Refresh a new access token for the user.
     *
     * @param $refreshToken
     * @return Token
     */
    public function refreshToken($refreshToken): Token;
}
