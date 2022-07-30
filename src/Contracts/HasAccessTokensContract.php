<?php

namespace Jundayw\LaravelOAuth\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Jundayw\LaravelOAuth\Token;

interface HasAccessTokensContract
{
    /**
     * Get the access tokens that belong to model.
     *
     * @return MorphMany
     */
    public function tokens(): MorphMany;

    /**
     * Create a new personal access token for the user.
     *
     * @param string $name
     * @param string $device
     * @return Token
     */
    public function createToken(string $name, string $device): Token;

    /**
     * Refresh a new oauth access token for the user.
     *
     * @param Request $request
     * @return Token
     */
    public function refreshToken(Request $request): Token;

    /**
     * Get the access token currently associated with the user.
     *
     * @return HasOAuthTokensContract
     */
    public function currentAccessToken(): HasOAuthTokensContract;

    /**
     * Set the current access token for the user.
     *
     * @param HasOAuthTokensContract $accessToken
     * @return HasAccessTokensContract
     */
    public function withAccessToken(HasOAuthTokensContract $accessToken): HasAccessTokensContract;
}
