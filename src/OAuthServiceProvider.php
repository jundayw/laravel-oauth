<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Jundayw\LaravelOAuth\Middleware\CheckForAnyScope;
use Jundayw\LaravelOAuth\Middleware\CheckScopes;

class OAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'auth.guards.oauth' => array_merge([
                'driver' => 'oauth',
                'provider' => null,
            ], config('auth.guards.oauth', [])),
        ]);

        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/oauth.php', 'oauth');
        }

        $this->addMiddlewareAlias('scopes', CheckScopes::class);
        $this->addMiddlewareAlias('scope', CheckForAnyScope::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'oauth-migrations');

            $this->publishes([
                __DIR__ . '/../config/oauth.php' => config_path('oauth.php'),
            ], 'oauth-config');
        }

        $this->configureGuard();
    }

    /**
     * Register Sanctum's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (OAuth::shouldRunMigrations()) {
            return $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Configure the Sanctum authentication guard.
     *
     * @return void
     */
    protected function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->extend('oauth', function ($app, $name, array $config) use ($auth) {
                return tap($this->createGuard($auth, $config), function ($guard) {
                    $this->app->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }

    /**
     * Register the guard.
     *
     * @param Factory $auth
     * @param array $config
     * @return RequestGuard
     */
    protected function createGuard(Factory $auth, array $config): RequestGuard
    {
        return new RequestGuard(
            new OAuthGuard($auth, $config['provider']),
            request(),
            $auth->createUserProvider($config['provider'] ?? null)
        );
    }

    /**
     * Register the middleware.
     *
     * @param $name
     * @param $class
     * @return mixed
     */
    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];

        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }

        return $router->middleware($name, $class);
    }

}
