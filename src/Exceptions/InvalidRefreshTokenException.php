<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class InvalidRefreshTokenException extends OAuthAccessTokenException
{
    public function __construct($message = 'Invalid refresh token.', $code = 42001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
