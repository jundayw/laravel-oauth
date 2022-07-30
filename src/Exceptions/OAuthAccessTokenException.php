<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use RuntimeException;
use Throwable;

class OAuthAccessTokenException extends RuntimeException
{
    public function __construct($message = "OAuthAccessTokenException", $code = 100, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
