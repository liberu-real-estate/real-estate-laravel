<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RightMoveService
{
    protected $baseUri;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.rightmove.base_uri');
        $this->apiKey = config('services.rightmove.api_key');
    }

    public function fetchProperties()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUri}/properties");

        if ($response->failed()) {
            throw new Exception('Failed to fetch properties from RightMove');
        }

        return $response->json();
    }

    public function createListing(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUri}/listings", $this->preparePropertyData($data));

        if ($response->failed()) {
            throw new Exception('Failed to create listing on RightMove');
        }

        return $response->json();
    }

    public function updateListing($listingId, array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->put("{$this->baseUri}/listings/{$listingId}", $this->preparePropertyData($data));

        if ($response->failed()) {
            throw new Exception('Failed to update listing on RightMove');
        }

        return $response->json();
    }

    public function syncAllProperties()
    {
        $properties = Property::all();
        $results = [];
    
        foreach ($properties as $property) {
            try {
                $results[] = $this->syncProperty($property);
            } catch (RightMoveApiException $e) {
                Log::error("RightMove API error for property {$property->id}: " . $e->getMessage(), [
                    'property_id' => $property->id,
                    'error_code' => $e->getCode(),
                    'error_details' => $e->getDetails(),
                ]);
                $results[] = ['id' => $property->id, 'status' => 'error', 'message' => $e->getMessage()];
            } catch (\Exception $e) {
                Log::error("Failed to sync property {$property->id} with RightMove: " . $e->getMessage(), [
                    'property_id' => $property->id,
                    'exception' => get_class($e),
                ]);
                $results[] = ['id' => $property->id, 'status' => 'error', 'message' => 'An unexpected error occurred'];
            }
        }
    
        return $results;
    }

    public function syncProperty(Property $property)
    {
        try {
            $rightMoveId = $property->rightmove_id;
    
            if ($rightMoveId) {
                return $this->updateListing($rightMoveId, $property->toArray());
            } else {
                $result = $this->createListing($property->toArray());
                $property->update(['rightmove_id' => $result['id']]);
                return $result;
            }
        } catch (\Exception $e) {
            throw new PropertySyncException(
                "Failed to sync property with RightMove: " . $e->getMessage(),
                $property->id,
                'RightMove',
                0,
                $e
            );
        }
    }

    protected function preparePropertyData(array $data)
    {
        // Map our database fields to RightMove's required fields
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
            // Add more fields as required by RightMove
        ];
    }
}