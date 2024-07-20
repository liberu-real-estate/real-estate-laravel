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
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
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

    'crm' => [
        'api_key' => env('CRM_API_KEY'),
        'endpoint' => env('CRM_ENDPOINT'),
        'sync_interval' => env('CRM_SYNC_INTERVAL', 15), // in minutes
    ],

];

   