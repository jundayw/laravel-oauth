<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class RefreshTokenExpiredException extends OAuthAccessTokenException
{
    public function __construct($message = 'Refresh token expired.', $code = 42002, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
