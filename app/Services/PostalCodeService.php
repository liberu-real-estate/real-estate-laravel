<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostalCodeService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.postcodes.api_key');
        $this->endpoint = config('services.postcodes.endpoint');
    }

    public function validatePostcode(string $postcode)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->endpoint . '/postcodes/' . $postcode);

            if ($response->successful()) {
                return $response->json('result');
            } else {
                Log::error('Postcode validation failed: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Postcode validation error: ' . $e->getMessage());
            return null;
        }
    }
}