<?php

namespace App\Services;

use App\Models\Property;
use App\Models\ZooplaSettings;

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

        foreach ($properties as $property) {
            if ($property->zoopla_id) {
                $this->updateProperty($property);
            } else {
                $this->uploadProperty($property);
            }
        }
    }

    protected function updateProperty(Property $property)
    {
        $success = $this->zooplaApiService->updateProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
        }
    }

    protected function uploadProperty(Property $property)
    {
        $success = $this->zooplaApiService->uploadProperty($property);
        if ($success) {
            $property->last_synced_at = now();
            $property->save();
        }
    }
}