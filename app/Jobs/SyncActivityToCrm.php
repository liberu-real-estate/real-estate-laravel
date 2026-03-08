<?php

namespace App\Jobs;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncActivityToCrm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Activity $activity) {}

    public function handle(): void
    {
        // TODO: Implement CRM synchronization for activity records.
        // This job should send activity data (type, description, timestamps)
        // to the configured CRM system via CrmIntegrationService.
    }
}
