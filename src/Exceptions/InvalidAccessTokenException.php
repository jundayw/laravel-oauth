<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class InvalidAccessTokenException extends OAuthAccessTokenException
{
    public function __construct($message = "InvalidAccessTokenException", $code = 110, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
