<?php

namespace App\Observers;

use App\Models\Property;
use App\Services\PropertyHistoryService;
use Illuminate\Support\Facades\Cache;

class PropertyObserver
{
    protected $historyService;

    public function __construct(PropertyHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Handle the Property "updating" event.
     */
    public function updating(Property $property): void
    {
        // Store original values in cache before update
        $original = $property->getOriginal();
        
        // Store in cache with unique key for this property update
        Cache::put("property_update_{$property->id}", $original, now()->addMinutes(5));
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        // Get the original values from cache
        $original = Cache::pull("property_update_{$property->id}");
        
        if ($original) {
            $this->historyService->autoTrackChanges($property, $original);
        }
    }

    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        // Track initial listing if property is created with a list_date
        if ($property->list_date) {
            $this->historyService->trackListing($property, $property->price);
        }
    }
}
