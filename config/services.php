    'rightmove' => [
        'base_uri' => env('RIGHTMOVE_BASE_URI'),
        'api_key' => env('RIGHTMOVE_API_KEY'),
    ],

    'onthemarket' => [
        'base_uri' => env('ONTHEMARKET_BASE_URI'),
        'api_key' => env('ONTHEMARKET_API_KEY'),
        'sync_frequency' => env('ONTHEMARKET_SYNC_FREQUENCY', 'hourly'),
    ],