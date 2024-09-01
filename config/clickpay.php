<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Clickpay Profile ID
    |--------------------------------------------------------------------------
    |
    | This is your Clickpay profile ID, which is required for making API
    | requests. Ensure that this value is provided in your environment
    | configuration file (.env).
    |
    */

    'profile_id' => env('CLICKPAY_PROFILE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Clickpay Server Key
    |--------------------------------------------------------------------------
    |
    | Your Clickpay server key is used to authenticate API requests. Make
    | sure to keep this value secure and do not expose it in your version
    | control system. It should be stored in the .env file.
    |
    */

    'server_key' => env('CLICKPAY_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The currency in which payments will be processed by default. You can
    | change this to any currency supported by Clickpay (e.g., USD, EUR).
    | This value can also be configured in your .env file.
    |
    */

    'currency' => env('CLICKPAY_CURRENCY', 'SAR'),

    /*
    |--------------------------------------------------------------------------
    | Clickpay API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Clickpay API. This is typically the endpoint where
    | API requests are sent. You can change this value if Clickpay provides
    | a different URL for specific regions or environments (e.g., testing).
    |
    */

    'base_url' => env('CLICKPAY_BASE_URL', 'https://secure.clickpay.com.sa'),

];
