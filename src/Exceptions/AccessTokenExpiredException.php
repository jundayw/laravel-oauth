<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class AccessTokenExpiredException extends OAuthAccessTokenException
{
    public function __construct($message = 'Access token expired.', $code = 41002, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
