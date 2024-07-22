<?php

namespace App\Jobs;

use App\Services\OnTheMarketService;

class SyncOnTheMarketProperties
{
    public function __invoke()
    {
        $onTheMarketService = app(OnTheMarketService::class);
        $onTheMarketService->syncAllProperties();
    }
}