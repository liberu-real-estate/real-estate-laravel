
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
                    $user->notify(new PropertyAlert($matchingProperties->toArray()));
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
                   $property->location == $alertCriteria->location;
        });
    }
}