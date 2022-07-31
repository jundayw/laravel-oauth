<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use RuntimeException;
use Throwable;

abstract class OAuthAccessTokenException extends RuntimeException
{
    public function __construct($message = '', $code = 40001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
