<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class NeighborhoodDataService
{
    protected $baseUri;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.neighborhood_data.base_uri');
        $this->apiKey = config('services.neighborhood_data.api_key');
    }

    public function getNeighborhoodData($zipCode)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUri}/neighborhood/{$zipCode}");

            if ($response->failed()) {
                throw new Exception('Failed to fetch neighborhood data');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Neighborhood data fetch failed: ' . $e->getMessage());
            return null;
        }
    }
}