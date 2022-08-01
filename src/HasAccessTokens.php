<?php

namespace Jundayw\LaravelOAuth;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\Contracts\HasOAuthTokensContract;

trait HasAccessTokens
{
    /**
     * The access token the user is using for the current request.
     *
     * @var HasOAuthTokensContract
     */
    protected $accessToken;

    /**
     * Get the access tokens that belong to model.
     *
     * @return MorphMany
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(OAuth::oAuthTokenModel(), 'tokenable');
    }

    /**
     * Create a new access token for the user.
     *
     * @param string $name
     * @param string $device
     * @param array $scopes
     * @return Token
     */
    public function createToken(string $name, string $device, array $scopes = ['*']): Token
    {
        $this->purgeToken($device);

        $token = $this->tokens()->create([
            'name' => $name,
            'device' => $device,
            'access_token' => hash(config('oauth.hash'), $plainTextAccessToken = Str::random(40)),
            'refresh_token' => hash(config('oauth.hash'), $plainTextRefreshToken = Str::random(40)),
            'access_token_expire_at' => now()->addSeconds(config('oauth.access_token_expire_in', 2 * 3600))->toDateTimeString(),
            'refresh_token_expire_at' => now()->addSeconds(config('oauth.refresh_token_expire_in', 24 * 3600))->toDateTimeString(),
            'scopes' => $scopes,
        ]);

        return new Token($token, $plainTextAccessToken, $plainTextRefreshToken);
    }

    /**
     * Multiple Devices && Concurrent Device
     *
     * @param string $device
     * @return int
     */
    private function purgeToken(string $device): int
    {
        $multipleDevices  = config('oauth.multiple_devices');
        $concurrentDevice = config('oauth.concurrent_device');

        if ($multipleDevices && $concurrentDevice) {
            return 0;
        }

        return $this->tokens()->getRelated()
            ->where([
                $this->tokens()->getForeignKeyName() => $this->tokens()->getParentKey(),
                $this->tokens()->getMorphType() => $this->tokens()->getMorphClass(),
            ])
            ->when($multipleDevices || $concurrentDevice, function($query) use ($multipleDevices, $concurrentDevice, $device) {
                $query->unless($multipleDevices, function($query) use ($device) {
                        $query->whereNotIn('device', [$device]);
                    })
                    ->unless($concurrentDevice, function($query) use ($device) {
                        $query->where('device', $device);
                    });
            })->delete();
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool
    {
        return $this->accessToken && $this->accessToken->can($scope);
    }

    /**
     * Get the access token currently associated with the user.
     *
     * @return HasOAuthTokensContract
     */
    public function currentAccessToken(): HasOAuthTokensContract
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     *
     * @param HasOAuthTokensContract $accessToken
     * @return HasAccessTokensContract
     */
    public function withAccessToken(HasOAuthTokensContract $accessToken): HasAccessTokensContract
    {
        $this->accessToken = $accessToken;

        return $this;
    }

}
