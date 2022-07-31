<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Use an encryption key when generating a secure access token for your application.
    | This can be set via environment variables at a more convenient time.
    |
    */

    'secret' => env('OAUTH_SECRET', env('APP_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Encryption Algorithm
    |--------------------------------------------------------------------------
    |
    | Supported: "md5", "sha256", "sha512", "ripemd128",
    |            "ripemd256", "gost", "crc32", "joaat"
    |
    */

    'hash' => 'sha256',

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | This configuration value allows you to customize the table name.
    |
    */

    'table' => 'oauth',

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

    /*
    |--------------------------------------------------------------------------
    | Cache Devices
    |--------------------------------------------------------------------------
    |
    | New features not implemented.
    |
    */

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
