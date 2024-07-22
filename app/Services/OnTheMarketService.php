<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OnTheMarketService
{
    protected $baseUri;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.onthemarket.base_uri');
        $this->apiKey = config('services.onthemarket.api_key');
    }

    public function syncAllProperties()
    {
        $properties = Property::all();
        $results = [];

        foreach ($properties as $property) {
            try {
                $results[] = $this->syncProperty($property);
            } catch (Exception $e) {
                Log::error("Failed to sync property {$property->id} with OnTheMarket: " . $e->getMessage());
                $results[] = ['id' => $property->id, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return $results;
    }

    protected function syncProperty(Property $property)
    {
        $onTheMarketId = $property->onthemarket_id;

        if ($onTheMarketId) {
            return $this->updateListing($onTheMarketId, $property->toArray());
        } else {
            $result = $this->createListing($property->toArray());
            $property->update(['onthemarket_id' => $result['id']]);
            return $result;
        }
    }

    protected function createListing(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUri}/listings", $this->preparePropertyData($data));

        if ($response->failed()) {
            throw new Exception('Failed to create listing on OnTheMarket');
        }

        return $response->json();
    }

    protected function updateListing($listingId, array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->put("{$this->baseUri}/listings/{$listingId}", $this->preparePropertyData($data));

        if ($response->failed()) {
            throw new Exception('Failed to update listing on OnTheMarket');
        }

        return $response->json();
    }

    protected function preparePropertyData(array $data)
    {
        // Map our database fields to OnTheMarket's required fields
        return [
            'propertyType' => $data['property_type'],
            'description' => $data['description'],
            'price' => $data['price'],
            'address' => [
                'street' => $data['location'],
                // Add more address fields as needed
            ],
            'bedrooms' => $data['bedrooms'],
            'bathrooms' => $data['bathrooms'],
            'area' => $data['area_sqft'],
            'yearBuilt' => $data['year_built'],
            // Add more fields as required by OnTheMarket
        ];
    }
}