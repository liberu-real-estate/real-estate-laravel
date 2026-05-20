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

    'digital_signature' => [
        'api_key' => env('DIGITAL_SIGNATURE_API_KEY'),
        'endpoint' => env('DIGITAL_SIGNATURE_ENDPOINT'),
        // Add any other necessary configuration options here
    ],

    'docusign' => [
        'account_id' => env('DOCUSIGN_ACCOUNT_ID'),
        'integration_key' => env('DOCUSIGN_INTEGRATION_KEY'),
        'secret_key' => env('DOCUSIGN_SECRET_KEY'),
        'base_path' => env('DOCUSIGN_BASE_PATH', 'https://demo.docusign.net/restapi'),
        'user_id' => env('DOCUSIGN_USER_ID'),
        'private_key_path' => env('DOCUSIGN_PRIVATE_KEY_PATH'),
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

    'sage_online' => [
        'api_key' => env('SAGE_ONLINE_API_KEY'),
        'endpoint' => env('SAGE_ONLINE_ENDPOINT'),
    ],

    'xero' => [
        'api_key' => env('XERO_API_KEY'),
        'endpoint' => env('XERO_ENDPOINT'),
    ],

    'walkscore' => [
        'api_key' => env('WALKSCORE_API_KEY'),
        'base_uri' => env('WALKSCORE_BASE_URI', 'https://api.walkscore.com'),
    ],

    'neighborhood_data' => [
        'base_uri' => env('NEIGHBORHOOD_DATA_BASE_URI', 'https://api.example.com'),
        'api_key' => env('NEIGHBORHOOD_DATA_API_KEY'),
    ],

    'zoopla' => [
        'base_uri' => env('ZOOPLA_BASE_URI'),
        'api_key' => env('ZOOPLA_API_KEY'),
        'sync_frequency' => env('ZOOPLA_SYNC_FREQUENCY', 'daily'),
    ],

    'holographic' => [
        'provider' => env('HOLOGRAPHIC_PROVIDER', 'looking_glass'),
        'api_key' => env('HOLOGRAPHIC_API_KEY'),
        'base_uri' => env('HOLOGRAPHIC_BASE_URI', 'https://api.lookingglassfactory.com'),
        'enable_web_viewer' => env('HOLOGRAPHIC_WEB_VIEWER', true),
    ],
];