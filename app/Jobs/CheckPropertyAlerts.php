
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alert;
use App\Models\Property;
use App\Notifications\PropertyAlert;
use Illuminate\Support\Facades\Log;

class CheckPropertyAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::info('Starting CheckPropertyAlerts job');

        try {
            $alerts = Alert::where('is_active', true)->get();

            foreach ($alerts as $alert) {
                $matchingProperties = $this->findMatchingProperties($alert);

                if ($matchingProperties->isNotEmpty()) {
                    $alert->user->notify(new PropertyAlert($matchingProperties, true));
                    Log::info('Sent property alert', ['user_id' => $alert->user_id, 'properties_count' => $matchingProperties->count()]);
                }
            }

            Log::info('CheckPropertyAlerts job completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in CheckPropertyAlerts job', ['error' => $e->getMessage()]);
        }
    }

    private function findMatchingProperties(Alert $alert)
    {
        $criteria = json_decode($alert->criteria, true);

        $query = Property::query();

        if (isset($criteria['property_type'])) {
            $query->where('type', $criteria['property_type']);
        }

        if (isset($criteria['min_price'])) {
            $query->where('price', '>=', $criteria['min_price']);
        }

        if (isset($criteria['max_price'])) {
            $query->where('price', '<=', $criteria['max_price']);
        }

        if (isset($criteria['bedrooms'])) {
            $query->where('bedrooms', $criteria['bedrooms']);
        }

        if (isset($criteria['bathrooms'])) {
            $query->where('bathrooms', $criteria['bathrooms']);
        }

        // Add more criteria as needed

        return $query->where('created_at', '>=', now()->subDay())->get();
    }
}