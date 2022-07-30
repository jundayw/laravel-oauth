<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class RefreshTokenExpiredException extends OAuthAccessTokenException
{
    public function __construct($message = "RefreshTokenExpiredException", $code = 130, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
