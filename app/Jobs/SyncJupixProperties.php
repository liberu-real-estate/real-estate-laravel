<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\JupixPortalSyncService;
use Illuminate\Support\Facades\Log;

class SyncJupixProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(JupixPortalSyncService $syncService)
    {
        Log::info('Starting Jupix property sync job');
        
        $result = $syncService->syncProperties();
        
        Log::info('Jupix property sync completed', [
            'synced' => $result['synced'],
            'failed' => $result['failed']
        ]);
    }
}