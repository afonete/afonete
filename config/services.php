<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
   
    'mailtrap' => [
        'token' => env('MAILTRAP_API_TOKEN'),
        'inbox_id' => env('MAILTRAP_INBOX_ID'),
    ],

    'plisio' => [
        'api_key' => env('PLISIO_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Direct Blockchain - Tron (TRC20 USDT)
    |--------------------------------------------------------------------------
    */
    'tron' => [
        'api_key' => env('TRONGRID_API_KEY'),
        'network' => env('TRON_NETWORK', 'mainnet'), // mainnet or testnet
        'hot_wallet' => env('TRON_HOT_WALLET_ADDRESS'),
        'usdt_contract' => env('TRON_USDT_CONTRACT', 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'),
    ],

];
