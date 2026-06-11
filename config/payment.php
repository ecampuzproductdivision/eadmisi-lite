<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Supported providers: 'midtrans', 'finnet', 'manual'
    |
    */

    'provider' => env('PAYMENT_PROVIDER', 'manual'),

    'midtrans' => [
        'server_key'    => env('MIDTRANS_SERVER_KEY'),
        'client_key'    => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
        'sanitized'     => true,
        '3ds'           => true,
    ],

    'finnet' => [
        'merchant_code' => env('FINNET_MERCHANT_CODE'),
        'api_key'       => env('FINNET_API_KEY'),
        'base_url'      => env('FINNET_BASE_URL', 'https://api.finnet.co.id'),
        'is_production' => env('FINNET_IS_PRODUCTION', false),
    ],
];