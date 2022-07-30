<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class InvalidRefreshTokenException extends OAuthAccessTokenException
{
    public function __construct($message = "InvalidRefreshTokenException", $code = 110, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
