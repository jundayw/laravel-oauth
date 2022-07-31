<?php

namespace Jundayw\LaravelOAuth\Contracts;

interface HasOAuthTokensContract
{
    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return HasOAuthTokensContract|null
     */
    public static function findAccessToken(string $token): ?HasOAuthTokensContract;

    /**
     * Determine if the token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function can(string $scope): bool;

    /**
     * Determine if the token is missing a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function cant(string $scope): bool;
}
