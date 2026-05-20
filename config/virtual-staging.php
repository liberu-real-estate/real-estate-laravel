<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Virtual Staging Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for the virtual staging feature.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Staging Provider
    |--------------------------------------------------------------------------
    |
    | The provider to use for virtual staging. Currently supported:
    | - 'mock' : Mock staging (copies original image with metadata)
    | - 'openai' : OpenAI DALL-E integration (requires API key)
    | - 'stable-diffusion' : Stable Diffusion integration (requires API key)
    |
    */

    'provider' => env('VIRTUAL_STAGING_PROVIDER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | API keys and endpoints for external staging providers
    |
    */

    'api' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_STAGING_MODEL', 'dall-e-3'),
        ],
        'stable_diffusion' => [
            'api_key' => env('STABLE_DIFFUSION_API_KEY'),
            'endpoint' => env('STABLE_DIFFUSION_ENDPOINT'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Staging Styles
    |--------------------------------------------------------------------------
    |
    | Available staging styles. You can customize this list based on your needs.
    |
    */

    'styles' => [
        'modern' => 'Modern',
        'traditional' => 'Traditional',
        'minimalist' => 'Minimalist',
        'luxury' => 'Luxury',
        'industrial' => 'Industrial',
        'scandinavian' => 'Scandinavian',
        'contemporary' => 'Contemporary',
        'rustic' => 'Rustic',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for image uploads and processing
    |
    */

    'images' => [
        'max_size' => env('VIRTUAL_STAGING_MAX_SIZE', 10240), // KB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/jpg'],
        'storage_disk' => 'public',
        'storage_path' => 'property-images',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for caching staged images
    |
    */

    'cache' => [
        'enabled' => env('VIRTUAL_STAGING_CACHE_ENABLED', true),
        'ttl' => env('VIRTUAL_STAGING_CACHE_TTL', 3600), // seconds
    ],

];
