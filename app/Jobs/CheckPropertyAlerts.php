<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\Property;
use App\Notifications\PropertyAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPropertyAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $savedSearches = SavedSearch::all();
    
        foreach ($savedSearches as $savedSearch) {
            $criteria = $savedSearch->criteria;
            $matchingProperties = Property::query()
                ->when(isset($criteria['property_type']), function ($query) use ($criteria) {
                    return $query->where('property_type', $criteria['property_type']);
                })
                ->when(isset($criteria['minPrice']), function ($query) use ($criteria) {
                    return $query->where('price', '>=', $criteria['minPrice']);
                })
                ->when(isset($criteria['maxPrice']), function ($query) use ($criteria) {
                    return $query->where('price', '<=', $criteria['maxPrice']);
                })
                ->when(isset($criteria['minBedrooms']), function ($query) use ($criteria) {
                    return $query->where('bedrooms', '>=', $criteria['minBedrooms']);
                })
                ->when(isset($criteria['maxBedrooms']), function ($query) use ($criteria) {
                    return $query->where('bedrooms', '<=', $criteria['maxBedrooms']);
                })
                ->when(isset($criteria['postalCode']), function ($query) use ($criteria) {
                    return $query->where('postal_code', 'like', $criteria['postalCode'] . '%');
                })
                ->where('created_at', '>', now()->subDay())
                ->get();
    
            foreach ($matchingProperties as $property) {
                $savedSearch->user->notify(new PropertyAlert($property));
            }
        }
    }
}