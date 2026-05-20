<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncZooplaProperties;

class SyncZooplaPropertiesCommand extends Command
{
    protected $signature = 'zoopla:sync-properties';
    protected $description = 'Sync properties with Zoopla';

    public function handle()
    {
        $this->info('Starting Zoopla property sync...');
        SyncZooplaProperties::dispatch();
        $this->info('Zoopla property sync job dispatched.');
    }
}