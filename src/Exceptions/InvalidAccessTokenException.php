<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class InvalidAccessTokenException extends OAuthAccessTokenException
{
    public function __construct($message = 'Invalid access token.', $code = 41001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
