<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncOnTheMarketProperties;

class SyncOnTheMarketPropertiesCommand extends Command
{
    protected $signature = 'onthemarket:sync-properties';
    protected $description = 'Sync properties with OnTheMarket';

    public function handle()
    {
        $this->info('Starting OnTheMarket property sync...');
        SyncOnTheMarketProperties::dispatch();
        $this->info('OnTheMarket property sync job dispatched.');
    }
}
