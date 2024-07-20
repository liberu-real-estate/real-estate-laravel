<?php

namespace App\Services;

use App\Models\Property;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OnTheMarketService
{
    protected $client;
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('onthemarket.api_key');
        $this->apiEndpoint = config('onthemarket.api_endpoint');
        $this->client = new Client([
            'base_uri' => $this->apiEndpoint,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function uploadProperty(Property $property)
    {
        try {
            $response = $this->client->post('properties', [
                'json' => $this->preparePropertyData($property),
            ]);

            if ($response->getStatusCode() === 201) {
                $property->onthemarket_id = json_decode($response->getBody())->id;
                $property->save();
                return true;
            }
        } catch (\Exception $e) {
            Log::error('OnTheMarket API Error: ' . $e->getMessage());
            return false;
        }
    }

    public function updateProperty(Property $property)
    {
        if (!$property->onthemarket_id) {
            return $this->uploadProperty($property);
        }

        try {
            $response = $this->client->put("properties/{$property->onthemarket_id}", [
                'json' => $this->preparePropertyData($property),
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('OnTheMarket API Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProperty($ontheMarketId)
    {
        try {
            $response = $this->client->delete("properties/{$ontheMarketId}");
            return $response->getStatusCode() === 204;
        } catch (\Exception $e) {
            Log::error('OnTheMarket API Error: ' . $e->getMessage());
            return false;
        }
    }

    protected function preparePropertyData(Property $property)
    {
        return [
            'title' => $property->title,
            'description' => $property->description,
            'property_type' => $property->property_type,
            'status' => $property->status,
            'price' => $property->price,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'area_sqft' => $property->area_sqft,
            'address' => $property->location,
            'images' => $property->images->pluck('url')->toArray(),
            // Add more fields as required by OnTheMarket API
        ];
    }
}