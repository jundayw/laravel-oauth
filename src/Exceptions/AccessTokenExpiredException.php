<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class AccessTokenExpiredException extends OAuthAccessTokenException
{
    public function __construct($message = "AccessTokenExpiredException", $code = 120, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
