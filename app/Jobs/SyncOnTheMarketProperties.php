<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\OnTheMarketService;
use Illuminate\Support\Facades\Log;

class SyncOnTheMarketProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(OnTheMarketService $onTheMarketService)
    {
        Log::info('Starting OnTheMarket property sync');
        try {
            $result = $onTheMarketService->syncAllProperties();
            Log::info('OnTheMarket property sync completed', ['result' => $result]);
        } catch (Exception $e) {
            Log::error('OnTheMarket sync job failed: ' . $e->getMessage());
            throw $e;
        }
    }
}