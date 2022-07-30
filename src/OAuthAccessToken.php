<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Database\Eloquent\Model;
use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;

class OAuthAccessToken extends Model implements HasOAuthTokensContract
{
    use HasOAuthTokens;

    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('oauth.table'));
    }
}
