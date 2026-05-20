<?php

namespace App\Jobs;

use App\Models\Property;
use App\Models\User;
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
        $recentProperties = Property::where('created_at', '>=', now()->subDay())
            ->orWhere('updated_at', '>=', now()->subDay())
            ->get();
    
        if ($recentProperties->isNotEmpty()) {
            $users = User::whereHas('propertyAlerts')->get();
    
            foreach ($users as $user) {
                $matchingProperties = $this->getMatchingProperties($user, $recentProperties);
    
                if ($matchingProperties->isNotEmpty()) {
                    $alertData = $this->prepareAlertData($user, $matchingProperties);
                    $user->notify(new PropertyAlert($alertData));
                }
            }
        }
    }
    
    private function getMatchingProperties($user, $properties)
    {
        return $properties->filter(function ($property) use ($user) {
            $alertCriteria = $user->propertyAlerts;
            return $property->price >= $alertCriteria->min_price &&
                   $property->price <= $alertCriteria->max_price &&
                   $property->bedrooms >= $alertCriteria->min_bedrooms &&
                   $property->bathrooms >= $alertCriteria->min_bathrooms &&
                   $property->location == $alertCriteria->location &&
                   $this->matchesAlertTypes($property, $alertCriteria);
        });
    }
    
    private function matchesAlertTypes($property, $alertCriteria)
    {
        foreach ($alertCriteria->alert_types as $alertType) {
            switch ($alertType) {
                case 'price_change':
                    if ($this->hasPriceChanged($property, $alertCriteria->price_change_threshold)) {
                        return true;
                    }
                    break;
                case 'new_listing':
                    if ($property->created_at->isToday()) {
                        return true;
                    }
                    break;
                case 'open_house':
                    if ($property->hasUpcomingOpenHouse()) {
                        return true;
                    }
                    break;
                case 'status_change':
                    if ($property->status_changed_at && $property->status_changed_at->isToday()) {
                        return true;
                    }
                    break;
            }
        }
        return false;
    }
    
    private function hasPriceChanged($property, $threshold)
    {
        $previousPrice = $property->price_history()->orderBy('created_at', 'desc')->skip(1)->first()->price ?? $property->price;
        $percentageChange = abs(($property->price - $previousPrice) / $previousPrice * 100);
        return $percentageChange >= $threshold;
    }
    
    private function prepareAlertData($user, $properties)
    {
        return $properties->map(function ($property) use ($user) {
            $alertTypes = [];
            $alertCriteria = $user->propertyAlerts;
    
            if (in_array('price_change', $alertCriteria->alert_types) && $this->hasPriceChanged($property, $alertCriteria->price_change_threshold)) {
                $alertTypes[] = 'Price Change';
            }
            if (in_array('new_listing', $alertCriteria->alert_types) && $property->created_at->isToday()) {
                $alertTypes[] = 'New Listing';
            }
            if (in_array('open_house', $alertCriteria->alert_types) && $property->hasUpcomingOpenHouse()) {
                $alertTypes[] = 'Open House';
            }
            if (in_array('status_change', $alertCriteria->alert_types) && $property->status_changed_at && $property->status_changed_at->isToday()) {
                $alertTypes[] = 'Status Change';
            }
    
            return [
                'property' => $property->toArray(),
                'alert_types' => $alertTypes,
            ];
        })->toArray();
    }
}
