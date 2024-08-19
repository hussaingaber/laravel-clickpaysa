<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Merchant profile id
    |--------------------------------------------------------------------------
    |
    | Your merchant profile id , you can find the profile id on your Clickpay Merchant’s Dashboard-profile.
    |
    */

    'profile_id' => env('CLICKPAY_PROFILE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Server Key
    |--------------------------------------------------------------------------
    |
    | You can find the Server key on your Clickpay Merchant’s Dashboard - Developers - Key management.
    |
    */

    'server_key' => env('CLICKPAY_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The currency you registered in with Clickpay account
    | Supported: "AED", "EGP", "SAR", "OMR", "JOD", "US"
    |
    */

    'currency' => env('CLICKPAY_CURRENCY'),
];
