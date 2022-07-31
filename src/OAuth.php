<?php

namespace Jundayw\LaravelOAuth;

class OAuth
{
    /**
     * The oauth token client model class name.
     *
     * @var string
     */
    protected static $oAuthTokenModel = 'Jundayw\\LaravelOAuth\\OAuthToken';

    /**
     * The refresh token client model class name.
     *
     * @var string
     */
    protected static $refreshTokenModel = 'Jundayw\\LaravelOAuth\\RefreshToken';

    /**
     * A callback that can get the token from the request.
     *
     * @var callable|null
     */
    public static $accessTokenRetrievalCallback;

    /**
     * A callback that can add to the validation of the access token.
     *
     * @var callable|null
     */
    public static $accessTokenAuthenticationCallback;

    /**
     * Indicates if Sanctum's migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Set the oauth access token model name.
     *
     * @param string $model
     * @return void
     */
    public static function oAuthTokenModelUsing(string $model)
    {
        static::$oAuthTokenModel = $model;
    }

    /**
     * Get the oauth token model class name.
     *
     * @return string
     */
    public static function oAuthTokenModel(): string
    {
        return static::$oAuthTokenModel;
    }

    /**
     * Set the refresh access token model name.
     *
     * @param string $model
     * @return void
     */
    public static function refreshTokenModelUsing(string $model)
    {
        static::$refreshTokenModel = $model;
    }

    /**
     * Get the refresh token model class name.
     *
     * @return string
     */
    public static function refreshTokenModel(): string
    {
        return static::$refreshTokenModel;
    }

    /**
     * Specify a callback that should be used to fetch the access token from the request.
     *
     * @param callable $callback
     * @return void
     */
    public static function accessTokenRetrievalCallbackUsing(callable $callback)
    {
        static::$accessTokenRetrievalCallback = $callback;
    }

    /**
     * Specify a callback that should be used to authenticate access tokens.
     *
     * @param callable $callback
     * @return void
     */
    public static function accessTokenAuthenticationCallbackUsing(callable $callback)
    {
        static::$accessTokenAuthenticationCallback = $callback;
    }

    /**
     * Determine if Sanctum's migrations should be run.
     *
     * @return bool
     */
    public static function shouldRunMigrations(): bool
    {
        return static::$runsMigrations;
    }

    /**
     * Configure Sanctum to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations(): OAuth
    {
        static::$runsMigrations = false;

        return new static;
    }

}
