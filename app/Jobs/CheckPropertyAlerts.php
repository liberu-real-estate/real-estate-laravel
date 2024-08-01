
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\PropertyAlertService;
use App\Notifications\PropertyAlert;
use Illuminate\Support\Facades\Log;

class CheckPropertyAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(PropertyAlertService $alertService)
    {
        Log::info('Starting property alert check');
        try {
            $alerts = $alertService->getActiveAlerts();

            foreach ($alerts as $alert) {
                $matchingProperties = $alertService->findMatchingProperties($alert);

                if (!empty($matchingProperties)) {
                    $user = $alert->user;
                    $user->notify(new PropertyAlert($matchingProperties, true));
                    Log::info('Property alert sent', [
                        'user_id' => $user->id,
                        'alert_id' => $alert->id,
                        'matching_properties' => count($matchingProperties)
                    ]);
                }
            }

            Log::info('Property alert check completed');
        } catch (\Exception $e) {
            Log::error('Property alert check failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}