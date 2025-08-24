<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JupixApiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.jupix.api_key');
        $this->baseUrl = config('services.jupix.base_url');
    }

    public function getProperties()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/properties');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Jupix API error: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Jupix API exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getProperty($jupixId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/properties/' . $jupixId);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Jupix API error: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Jupix API exception: ' . $e->getMessage());
            return null;
        }
    }
}