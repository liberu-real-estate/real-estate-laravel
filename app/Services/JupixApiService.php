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

    public function getValuations($propertyId = null)
    {
        try {
            $url = $this->baseUrl . '/valuations';
            if ($propertyId) {
                $url .= '?property_id=' . $propertyId;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($url);

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

    public function getChains()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/chains');

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

    public function getCompliance($propertyId = null)
    {
        try {
            $url = $this->baseUrl . '/compliance';
            if ($propertyId) {
                $url .= '?property_id=' . $propertyId;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($url);

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

    public function getVendors()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/vendors');

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

    public function syncProperty($propertyData)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/properties/sync', $propertyData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Jupix API sync error: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Jupix API sync exception: ' . $e->getMessage());
            return null;
        }
    }

    public function syncValuation($valuationData)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/valuations/sync', $valuationData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Jupix API valuation sync error: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Jupix API valuation sync exception: ' . $e->getMessage());
            return null;
        }
    }