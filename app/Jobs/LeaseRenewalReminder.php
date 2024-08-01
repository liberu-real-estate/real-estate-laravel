<?php

namespace App\Jobs;

use App\Models\Lease;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LeaseRenewalReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NotificationService $notificationService)
    {
        $leasesUpForRenewal = Lease::where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->get();

        foreach ($leasesUpForRenewal as $lease) {
            $notificationService->sendLeaseRenewalReminder($lease->tenant, $lease);
        }
    }
}