<?php

namespace Jundayw\LaravelOAuth\Contracts;

use Illuminate\Http\Request;
use Jundayw\LaravelOAuth\Token;

interface HasRefreshTokensContract
{
    /**
     * Refresh a new access token for the user.
     *
     * @param Request $request
     * @return Token
     */
    public static function refreshToken(Request $request): Token;
}
