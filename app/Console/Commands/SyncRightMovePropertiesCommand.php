<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncRightMoveProperties;

class SyncRightMovePropertiesCommand extends Command
{
    protected $signature = 'rightmove:sync-properties';
    protected $description = 'Sync properties with RightMove';

    public function handle()
    {
        $this->info('Starting RightMove property sync...');
        SyncRightMoveProperties::dispatch();
        $this->info('RightMove property sync job dispatched.');
    }
}
