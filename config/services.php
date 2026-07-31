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
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => '',
    ],

    'bluedart' => [
        'client_id' => env('BLUEDART_CLIENT_ID'),
        'client_secret' => env('BLUEDART_CLIENT_SECRET'),
        'login_id' => env('BLUEDART_LOGIN_ID'),
        'licence_key' => env('BLUEDART_LICENCE_KEY'),
        'tracking_licence_key' => env('BLUEDART_TRACKING_LICENCE_KEY'),
        'customer_code' => env('BLUEDART_CUSTOMER_CODE'),
        'origin_area' => env('BLUEDART_ORIGIN_AREA'),
        'api_base_url' => env('BLUEDART_API_BASE_URL'),
    ],

];
