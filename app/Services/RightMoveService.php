<?php

namespace App\Services;

use App\Models\Property;
use App\Exceptions\RightMoveApiException;
use App\Exceptions\PropertySyncException;
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

    public function createListing(Property $property): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUri}/listings", $this->preparePropertyData($property));

        if ($response->failed()) {
            throw new RightMoveApiException('Failed to create listing on RightMove', $response->status());
        }

        return $response->json();
    }

    public function updateListing(string $listingId, Property $property): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->put("{$this->baseUri}/listings/{$listingId}", $this->preparePropertyData($property));

        if ($response->failed()) {
            throw new RightMoveApiException('Failed to update listing on RightMove', $response->status());
        }

        return $response->json();
    }

    public function deleteListing(string $listingId): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete("{$this->baseUri}/listings/{$listingId}");

        if ($response->failed()) {
            throw new RightMoveApiException('Failed to delete listing on RightMove', $response->status());
        }

        return true;
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
            } catch (Exception $e) {
                Log::error("Failed to sync property {$property->id} with RightMove: " . $e->getMessage(), [
                    'property_id' => $property->id,
                    'exception' => get_class($e),
                ]);
                $results[] = ['id' => $property->id, 'status' => 'error', 'message' => 'An unexpected error occurred'];
            }
        }
    
        return $results;
    }

    public function syncProperty(Property $property): array
    {
        try {
            $rightMoveId = $property->rightmove_id;

            if ($rightMoveId) {
                return $this->updateListing($rightMoveId, $property);
            } else {
                $result = $this->createListing($property);
                $property->update(['rightmove_id' => $result['id']]);
                return $result;
            }
        } catch (Exception $e) {
            throw new PropertySyncException(
                "Failed to sync property with RightMove: " . $e->getMessage(),
                $property->id,
                'RightMove',
                0,
                $e
            );
        }
    }

    protected function preparePropertyData(Property $property): array
    {
        return [
            'propertyType' => $property->property_type,
            'description' => $property->description,
            'price' => $property->price,
            'address' => [
                'street' => $property->location,
                'postalCode' => $property->postal_code,
                'country' => $property->country,
            ],
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'area' => $property->area_sqft,
            'yearBuilt' => $property->year_built,
            'status' => $property->status,
        ];
    }
}