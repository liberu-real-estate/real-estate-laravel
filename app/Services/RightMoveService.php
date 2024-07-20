<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RightMoveService
{
    protected $apiKey;
    protected $endpoint;
    protected $networkId;
    protected $branchId;

    public function __construct()
    {
        $this->apiKey = config('services.rightmove.api_key');
        $this->endpoint = config('services.rightmove.endpoint');
        $this->networkId = config('services.rightmove.network_id');
        $this->branchId = config('services.rightmove.branch_id');
    }

    public function uploadProperty(Property $property)
    {
        $data = $this->preparePropertyData($property);
        
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->post("{$this->endpoint}/properties", $data);

            if ($response->successful()) {
                $rightmoveId = $response->json('id');
                $property->update(['rightmove_id' => $rightmoveId, 'rightmove_status' => 'active']);
                return true;
            } else {
                Log::error("RightMove API Error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("RightMove API Exception: " . $e->getMessage());
            return false;
        }
    }

    public function updateProperty(Property $property)
    {
        if (!$property->rightmove_id) {
            return $this->uploadProperty($property);
        }

        $data = $this->preparePropertyData($property);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->put("{$this->endpoint}/properties/{$property->rightmove_id}", $data);

            if ($response->successful()) {
                $property->update(['rightmove_status' => 'active']);
                return true;
            } else {
                Log::error("RightMove API Error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("RightMove API Exception: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProperty(Property $property)
    {
        if (!$property->rightmove_id) {
            return true;
        }

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->delete("{$this->endpoint}/properties/{$property->rightmove_id}");

            if ($response->successful()) {
                $property->update(['rightmove_id' => null, 'rightmove_status' => 'inactive']);
                return true;
            } else {
                Log::error("RightMove API Error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("RightMove API Exception: " . $e->getMessage());
            return false;
        }
    }

    protected function preparePropertyData(Property $property)
    {
        return [
            'network' => [
                'networkId' => $this->networkId,
            ],
            'branch' => [
                'branchId' => $this->branchId,
            ],
            'property' => [
                'propertyType' => $property->property_type,
                'status' => $property->status,
                'price' => [
                    'amount' => $property->price,
                    'currency' => 'GBP',
                ],
                'address' => [
                    'street' => $property->location,
                    // Add more address fields as needed
                ],
                'details' => [
                    'summary' => $property->title,
                    'description' => $property->description,
                    'features' => $property->features->pluck('name')->toArray(),
                ],
                'rooms' => [
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                ],
                'media' => [
                    'images' => $property->images->map(function ($image) {
                        return ['url' => $image->url];
                    })->toArray(),
                ],
                // Add more fields as required by RightMove API
            ],
        ];
    }
}