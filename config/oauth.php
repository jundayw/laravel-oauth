<?php

return [

    'secret' => env('OAUTH_SECRET', env('APP_KEY', 'OAUTH_SECRET')),

    'hash' => 'sha256',

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | This configuration value allows you to customize the table name
    |
    */

    'table' => 'oauth_test',

    /*
    |--------------------------------------------------------------------------
    | Expiration Seconds
    |--------------------------------------------------------------------------
    |
    | This value controls the number of seconds until an access token will be
    | considered expired. If this value is null, access tokens do
    | not expire.
    |
    */

    'access_token_expire_in' => 2 * 3600,

    'refresh_token_expire_in' => 24 * 3600 * 15,

    'cache' => [
        'driver' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Multiple Devices
    |--------------------------------------------------------------------------
    |
    | This option defines whether the default setting allows
    | multiple devices to be online at the same time.
    |
    */

    'multiple_devices' => true,

    /*
    |--------------------------------------------------------------------------
    | Concurrent Device
    |--------------------------------------------------------------------------
    |
    | This option defines whether the same device is allowed to be online
    | at the same time by default.
    |
    */

    'concurrent_device' => true,
];
