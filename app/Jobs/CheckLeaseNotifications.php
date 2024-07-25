<?php

namespace App\Jobs;

use App\Models\Lease;
use App\Notifications\LeaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckLeaseNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $leasesUpForRenewal = Lease::where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->get();

        foreach ($leasesUpForRenewal as $lease) {
            $lease->tenant->notify(new LeaseNotification($lease, 'renewal'));
        }

        $terminatedLeases = Lease::where('status', 'terminated')
            ->where('updated_at', '>=', now()->subDay())
            ->get();

        foreach ($terminatedLeases as $lease) {
            $lease->tenant->notify(new LeaseNotification($lease, 'termination'));
        }
    }
}