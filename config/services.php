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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1')
    ],
    'rightmove' => [
        'base_uri' => env('RIGHTMOVE_BASE_URI'),
        'api_key' => env('RIGHTMOVE_API_KEY'),
    ],

    'onthemarket' => [
        'base_uri' => env('ONTHEMARKET_BASE_URI'),
        'api_key' => env('ONTHEMARKET_API_KEY'),
        'sync_frequency' => env('ONTHEMARKET_SYNC_FREQUENCY', 'hourly'),
    ],


    'stripe' => [
        'secret_key' => config('stripe.secret_key'),
        'publishable_key' => config('stripe.publishable_key'),
    ],

    'accounting' => [
        'system' => env('ACCOUNTING_SYSTEM', 'quickbooks'), // 'quickbooks', 'sage', or 'xero'
    ],

    'quickbooks' => [
        'api_key' => env('QUICKBOOKS_API_KEY'),
        'endpoint' => env('QUICKBOOKS_ENDPOINT'),
    ],

    'sage' => [
        'api_key' => env('SAGE_API_KEY'),
        'endpoint' => env('SAGE_ENDPOINT'),
    ],

    'xero' => [
        'api_key' => env('XERO_API_KEY'),
        'endpoint' => env('XERO_ENDPOINT'),
    ],

    'digital_signature' => [
        'api_key' => env('DIGITAL_SIGNATURE_API_KEY'),
        'endpoint' => env('DIGITAL_SIGNATURE_ENDPOINT'),
        // Add any other necessary configuration options here
    ],

    'crm' => [
        'api_key' => env('CRM_API_KEY'),
        'endpoint' => env('CRM_ENDPOINT'),
        'sync_interval' => env('CRM_SYNC_INTERVAL', 15), // in minutes
    ],

    'jupix' => [
        'api_key' => env('JUPIX_API_KEY'),
        'base_url' => env('JUPIX_BASE_URL'),
    ],

    'lets_safe' => [
        'api_key' => env('LETS_SAFE_API_KEY'),
        'api_url' => env('LETS_SAFE_API_URL', 'https://api.letssafe.com/v1'),
    ],
];



