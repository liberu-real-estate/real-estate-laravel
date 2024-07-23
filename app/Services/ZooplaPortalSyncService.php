<?php

namespace App\Services;

use App\Models\Property;
use App\Models\ZooplaSettings;
use Illuminate\Support\Facades\Log;

class ZooplaPortalSyncService
{
    protected $zooplaApiService;
    protected $zooplaSettings;

    public function __construct(ZooplaApiService $zooplaApiService, ZooplaSettings $zooplaSettings)
    {
        $this->zooplaApiService = $zooplaApiService;
        $this->zooplaSettings = $zooplaSettings;
    }

    public function syncProperties()
    {
        $properties = Property::needsSyncing()->get();
        $batchSize = 100;
        $totalProperties = $properties->count();
        $successCount = 0;
        $failureCount = 0;

        Log::info("Starting Zoopla property sync. Total properties: {$totalProperties}");

        $properties->chunk($batchSize)->each(function ($batch) use (&$successCount, &$failureCount) {
            foreach ($batch as $property) {
                try {
                    if ($property->zoopla_id) {
                        $this->updateProperty($property);
                    } else {
                        $this->uploadProperty($property);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to sync property {$property->id}: " . $e->getMessage());
                    $failureCount++;
                }
            }
        });

        Log::info("Zoopla property sync completed. Successes: {$successCount}, Failures: {$failureCount}");
    }

    public function syncSingleProperty(Property $property)
    {
        try {
            if ($property->zoopla_id) {
                $this->updateProperty($property);
            } else {
                $this->uploadProperty($property);
            }
            Log::info("Successfully synced property {$property->id} with Zoopla");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to sync property {$property->id} with Zoopla: " . $e->getMessage());
            return false;
        }
    }

    protected function updateProperty(Property $property)
    {
        $success = $this->zooplaApiService->updateProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
            Log::info("Updated property {$property->id} on Zoopla");
        } else {
            Log::warning("Failed to update property {$property->id} on Zoopla");
        }
    }

    protected function uploadProperty(Property $property)
    {
        $success = $this->zooplaApiService->uploadProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
            Log::info("Uploaded property {$property->id} to Zoopla");
        } else {
            Log::warning("Failed to upload property {$property->id} to Zoopla");
        }
    }
}