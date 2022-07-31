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
     * Create a new access token for the user.
     *
     * @param string $name
     * @param string $device
     * @param array $scopes
     * @return Token
     */
    public function createToken(string $name, string $device, array $scopes = ['*']): Token;

    /**
     * Determine if the current API token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool;

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
