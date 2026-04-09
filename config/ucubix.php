<?php

return [

    /*
    |--------------------------------------------------------------------------
    | UCubix API Key
    |--------------------------------------------------------------------------
    |
    | Your UCubix API key used for authenticating requests.
    |
    */
    'api_key' => env('UCUBIX_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | UCubix API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL of the UCubix API.
    |
    */
    'base_url' => env('UCUBIX_BASE_URL', 'https://ucubix.com/api/v1/'),

    /*
    |--------------------------------------------------------------------------
    | Max Retry on Rate Limit
    |--------------------------------------------------------------------------
    |
    | Maximum number of retries when a 429 rate limit response is received.
    |
    */
    'max_retry_on_rate_limit' => env('UCUBIX_MAX_RETRY', 3),

];
