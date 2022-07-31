<?php

namespace Jundayw\LaravelOAuth;

use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;
use Jundayw\LaravelOAuth\Contracts\HasTokenableContract;
use Jundayw\LaravelOAuth\Models\OAuth;

class OAuthToken extends OAuth implements HasOAuthTokensContract, HasTokenableContract
{
    use HasOAuthTokens, HasTokenable;
}
