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
        $synced = 0;
        $failed = 0;

        foreach ($properties as $property) {
            try {
                $success = $property->zoopla_id
                    ? $this->updateProperty($property)
                    : $this->uploadProperty($property);

                if ($success) {
                    $synced++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error('Error syncing property with Zoopla', [
                    'property_id' => $property->id,
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    protected function updateProperty(Property $property)
    {
        $success = $this->zooplaApiService->updateProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
        }
        return $success;
    }

    protected function uploadProperty(Property $property)
    {
        $success = $this->zooplaApiService->uploadProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
        }
        return $success;
    }
}