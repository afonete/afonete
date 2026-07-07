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

    /*
    |--------------------------------------------------------------------------
    | Direct Blockchain - Tron (TRC20 USDT)
    |--------------------------------------------------------------------------
    */
    'tron' => [
        'api_key'                => env('TRONGRID_API_KEY'),
        'network'                => env('TRON_NETWORK', 'mainnet'), // mainnet or shasta
        'base_url'               => env('TRON_FULL_HOST', env('TRON_NETWORK', 'mainnet') === 'shasta' ? 'https://api.shasta.trongrid.io' : 'https://api.trongrid.io'),
        'hot_wallet'                    => env('TRON_HOT_WALLET_ADDRESS'),
        'hot_wallet_private_key'        => env('TRON_HOT_WALLET_PRIVATE_KEY'),
        'fee_wallet'                    => env('TRON_FEE_WALLET_ADDRESS'),
        'fee_wallet_private_key'        => env('TRON_FEE_WALLET_PRIVATE_KEY'),
        'usdt_contract'                 => env('TRON_USDT_CONTRACT', 'TKu83PAfPbGd6KmoUx8dmST2qpdb2nnV8w'),
        'fee_limit_trx'                 => env('TRON_USDT_FEE_LIMIT_TRX', 50),
        'deposit_sweep_min_usdt'        => env('TRON_DEPOSIT_SWEEP_MIN_USDT', 10),
        'deposit_sweep_min_trx'         => env('TRON_DEPOSIT_SWEEP_MIN_TRX', 20),
        'auto_fund_deposit_addresses'   => env('TRON_AUTO_FUND_DEPOSIT_ADDRESSES', false),
        'deposit_sweep_target_trx'      => env('TRON_DEPOSIT_SWEEP_TARGET_TRX', 25),
        'max_trx_fund_per_address'      => env('TRON_MAX_TRX_FUND_PER_ADDRESS', 30),
        'max_trx_fund_per_run'          => env('TRON_MAX_TRX_FUND_PER_RUN', 200),
        'fee_wallet_min_reserve_trx'    => env('TRON_FEE_WALLET_MIN_RESERVE_TRX', 50),
        'hot_wallet_min_trx_for_sweep'  => env('TRON_HOT_WALLET_MIN_TRX_FOR_SWEEP', 20),
    ],

];
