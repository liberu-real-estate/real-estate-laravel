<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncJupixProperties;

class SyncJupixPropertiesCommand extends Command
{
    protected $signature = 'jupix:sync-properties';
    protected $description = 'Sync properties with Jupix';

    public function handle()
    {
        $this->info('Starting Jupix property sync...');
        SyncJupixProperties::dispatch();
        $this->info('Jupix property sync job dispatched.');
    }
}