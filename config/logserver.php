<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Host
    |--------------------------------------------------------------------------
    |
    | Provide full host url
    | Eg. https://laravel-log-server-url.com
    |
    */
    'host' => env('LOG_SERVER_HOST'),

    /*
     |--------------------------------------------------------------------------
     | Endpoint
     |--------------------------------------------------------------------------
     |
     | Eg. 'api/v1/logger' without leading or trailing '/'
     |
     */
    'endpoint' => env('LOG_SERVER_ENDPOINT', 'api/logger'),

    /*
     |--------------------------------------------------------------------------
     | Credentials
     |--------------------------------------------------------------------------
     |
     | For acquiring  Client Credentials Grant Token
     |
     */
    'client_id' => env('LOG_SERVER_CLIENT_ID'),
    'client_secret' => env('LOG_SERVER_CLIENT_SECRET'),

    /*
     |--------------------------------------------------------------------------
     | Classes
     |--------------------------------------------------------------------------
     |
     | Use different formatter class
     |
     */
    'classes' => [
        'formatter' => \RichPeers\LaravelLogOAuth2Curl\Monolog\StackTraceJsonFormatter::class,
    ]
];
