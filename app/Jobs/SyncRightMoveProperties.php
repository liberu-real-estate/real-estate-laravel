<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\RightMoveService;
use Illuminate\Support\Facades\Log;

class SyncRightMoveProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(RightMoveService $rightMoveService)
    {
        Log::info('Starting RightMove property sync');
        try {
            $result = $rightMoveService->syncAllProperties();
            Log::info('RightMove property sync completed', ['result' => $result]);
        } catch (Exception $e) {
            Log::error('RightMove sync job failed: ' . $e->getMessage());
            throw $e;
        }
    }
}