<?php

namespace App\Jobs;

use Exception;
use App\Services\RightMoveService;

class SyncRightMoveProperties
{
    public function handle()
    {
        try {
            $rightMoveService = app(RightMoveService::class);
            $rightMoveService->syncAllProperties();
        } catch (Exception $e) {
            Log::error('RightMove sync job failed: ' . $e->getMessage());
            throw $e;
        }
    }
}