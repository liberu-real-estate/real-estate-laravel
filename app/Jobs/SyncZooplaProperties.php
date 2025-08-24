<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ZooplaPortalSyncService;
use Illuminate\Support\Facades\Log;

class SyncZooplaProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(ZooplaPortalSyncService $syncService)
    {
        Log::info('Starting Zoopla property sync');
        try {
            $result = $syncService->syncProperties();
            Log::info('Zoopla property sync completed', [
                'synced_count' => $result['synced'],
                'failed_count' => $result['failed']
            ]);
        } catch (Exception $e) {
            Log::error('Zoopla property sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}