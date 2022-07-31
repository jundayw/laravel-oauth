<?php

namespace Jundayw\LaravelOAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jundayw\LaravelOAuth\Exceptions\InvalidAccessTokenException;
use Jundayw\LaravelOAuth\Exceptions\MissingScopeException;

class CheckForAnyScope
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$scopes
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            throw new InvalidAccessTokenException();
        }

        foreach ($scopes as $scope) {
            if ($request->user()->tokenCan($scope)) {
                return $next($request);
            }
        }

        throw new MissingScopeException($scopes);
    }
}
