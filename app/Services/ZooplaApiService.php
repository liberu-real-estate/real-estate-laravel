<?php

namespace App\Services;

use App\Models\Property;
use App\Models\ZooplaSettings;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ZooplaApiService
{
    protected $client;
    protected $zooplaSettings;

    public function __construct(ZooplaSettings $zooplaSettings)
    {
        $this->zooplaSettings = $zooplaSettings;
        $this->client = new Client([
            'base_uri' => $zooplaSettings->base_uri,
            'headers' => [
                'Authorization' => 'Bearer ' . $zooplaSettings->api_key,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function uploadProperty(Property $property, $retries = 3)
    {
        for ($attempt = 1; $attempt <= $retries; $attempt++) {
            try {
                $response = $this->client->post('properties', [
                    'json' => $this->preparePropertyData($property),
                ]);
    
                if ($response->getStatusCode() === 201) {
                    $property->zoopla_id = json_decode($response->getBody())->id;
                    $property->save();
                    Log::info("Property uploaded to Zoopla successfully", ['property_id' => $property->id, 'zoopla_id' => $property->zoopla_id]);
                    return true;
                }
            } catch (\Exception $e) {
                Log::warning("Zoopla API Error (Attempt $attempt/$retries): " . $e->getMessage(), [
                    'property_id' => $property->id,
                    'exception' => get_class($e),
                ]);
    
                if ($attempt === $retries) {
                    Log::error("Failed to upload property to Zoopla after $retries attempts", ['property_id' => $property->id]);
                    return false;
                }
    
                sleep(pow(2, $attempt)); // Exponential backoff
            }
        }
    }

    public function updateProperty(Property $property)
    {
        if (!$property->zoopla_id) {
            return $this->uploadProperty($property);
        }

        try {
            $response = $this->client->put("properties/{$property->zoopla_id}", [
                'json' => $this->preparePropertyData($property),
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('Zoopla API Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProperty($zooplaId)
    {
        try {
            $response = $this->client->delete("properties/{$zooplaId}");
            return $response->getStatusCode() === 204;
        } catch (\Exception $e) {
            Log::error('Zoopla API Error: ' . $e->getMessage());
            return false;
        }
    }

    protected function preparePropertyData(Property $property)
    {
        $data = [
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
            // Add more fields as required by Zoopla API
        ];
    
        Log::info('Preparing Zoopla property data', [
            'property_id' => $property->id,
            'zoopla_id' => $property->zoopla_id,
            'data' => $data,
        ]);
    
        return $data;
    }
}