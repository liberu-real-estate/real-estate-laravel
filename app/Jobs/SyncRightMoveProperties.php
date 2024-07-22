<?php

namespace App\Jobs;

use App\Services\RightMoveService;

class SyncRightMoveProperties
{
    public function __invoke()
    {
        $rightMoveService = app(RightMoveService::class);
        $rightMoveService->syncAllProperties();
    }
}