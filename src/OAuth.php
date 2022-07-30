<?php

namespace Jundayw\LaravelOAuth;

class OAuth
{
    /**
     * The oauth access token client model class name.
     *
     * @var string
     */
    public static $oAuthAccessTokenModel = 'Jundayw\\LaravelOAuth\\OAuthAccessToken';

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
    public static function useOAuthAccessTokenModel(string $model)
    {
        static::$oAuthAccessTokenModel = $model;
    }

    /**
     * Get the oauth access token model class name.
     *
     * @return string
     */
    public static function oAuthAccessTokenModel(): string
    {
        return static::$oAuthAccessTokenModel;
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
