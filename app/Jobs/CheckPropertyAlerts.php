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
        $alerts = Alert::all();

        foreach ($alerts as $alert) {
            $matchingProperties = Property::query()
                ->where('property_type', $alert->property_type)
                ->where('price', '>=', $alert->min_price)
                ->where('price', '<=', $alert->max_price)
                ->where('bedrooms', '>=', $alert->min_bedrooms)
                ->where('bedrooms', '<=', $alert->max_bedrooms)
                ->where('location', 'like', '%' . $alert->location . '%')
                ->where('created_at', '>', now()->subDay())
                ->get();

            foreach ($matchingProperties as $property) {
                $alert->user->notify(new PropertyAlert($property));
            }
        }
    }
}