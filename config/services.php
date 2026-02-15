<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // KONFIGURASI GOOGLE LOGIN
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    // Midtrans tetap aman di sini
    'midtrans' => [
        'merchantId' => env('MIDTRANS_MERCHANT_ID'),
        'clientKey'  => env('MIDTRANS_CLIENT_KEY'),
        'serverKey'  => env('MIDTRANS_SERVER_KEY'),
        'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
        'isSanitized'  => true,
        'is3ds'        => true,
    ],

];