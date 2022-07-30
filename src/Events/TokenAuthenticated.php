<?php

namespace Jundayw\LaravelOAuth\Events;

use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;

class TokenAuthenticated
{
    /**
     * The oauth access token that was authenticated.
     *
     * @var HasOAuthTokensContract
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param HasOAuthTokensContract $token
     * @return void
     */
    public function __construct(HasOAuthTokensContract $token)
    {
        $this->token = $token;
    }
}
