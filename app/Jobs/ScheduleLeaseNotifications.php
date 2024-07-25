<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Lease;
use App\Notifications\LeaseNotification;

class ScheduleLeaseNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $leases = Lease::where('status', 'active')->get();

        foreach ($leases as $lease) {
            if ($lease->isUpForRenewal()) {
                $lease->tenant->notify(new LeaseNotification($lease, 'renewal'));
            }

            if ($lease->end_date->isPast()) {
                $lease->tenant->notify(new LeaseNotification($lease, 'termination'));
                $lease->terminate($lease->end_date);
            }
        }
    }
}