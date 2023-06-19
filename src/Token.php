<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Jundayw\LaravelOAuth\Contracts\HasTokenableContract;
use Jundayw\LaravelOAuth\Support\Signature;

class Token implements Arrayable, Jsonable
{
    use Signature;

    private $token;

    public function __construct(HasTokenableContract $token)
    {
        $this->token = $token;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        $plaintext = [
            'access_token' => $this->token->access_token,
            'refresh_token' => $this->token->refresh_token,
            'access_token_expire_in' => config('oauth.access_token_expire_in'),
            'refresh_token_expire_in' => config('oauth.refresh_token_expire_in'),
            'hash' => config('oauth.hash'),
        ];

        return array_merge($plaintext, [
            'authorization' => join(' ', ['Bearer', Token::encrypt($plaintext)]),
        ]);
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->toArray(), $options);
    }
}
