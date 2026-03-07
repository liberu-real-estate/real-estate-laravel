<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncLeadToCrm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Lead $lead) {}

    public function handle(): void
    {
        // CRM sync logic placeholder
    }
}
