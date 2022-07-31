<?php

namespace Jundayw\LaravelOAuth;

use Jundayw\LaravelOAuth\Contracts\HasRefreshTokensContract;
use Jundayw\LaravelOAuth\Contracts\HasTokenableContract;
use Jundayw\LaravelOAuth\Models\OAuth;

class RefreshToken extends OAuth implements HasRefreshTokensContract, HasTokenableContract
{
    use HasRefreshTokens, HasTokenable;
}
