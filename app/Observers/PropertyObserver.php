<?php

namespace App\Observers;

use App\Models\Property;
use App\Services\PropertyHistoryService;

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
        // Store original values before update
        $original = $property->getOriginal();
        
        // We'll track changes after the update in the updated event
        $property->_originalBeforeUpdate = $original;
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        // Get the original values stored in updating event
        if (isset($property->_originalBeforeUpdate)) {
            $this->historyService->autoTrackChanges($property, $property->_originalBeforeUpdate);
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
