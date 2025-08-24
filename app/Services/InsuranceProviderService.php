<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InsuranceProviderService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.insurance.api_key');
        $this->endpoint = config('services.insurance.endpoint');
    }

    public function getQuote(array $propertyData)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/quotes', $propertyData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Insurance quote request failed: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Insurance quote request error: ' . $e->getMessage());
            return null;
        }
    }

    public function purchasePolicy(array $policyData)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/policies', $policyData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Insurance policy purchase failed: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Insurance policy purchase error: ' . $e->getMessage());
            return null;
        }
    }
}