<?php

return [
    'api_key' => env('ZOOPLA_API_KEY'),
    'api_endpoint' => env('ZOOPLA_API_ENDPOINT', 'https://api.zoopla.co.uk/api/v1/'),
    'sync_frequency' => env('ZOOPLA_SYNC_FREQUENCY', 'hourly'),
];