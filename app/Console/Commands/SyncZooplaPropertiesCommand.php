<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncZooplaProperties;
use App\Models\ZooplaSettings;

class SyncZooplaPropertiesCommand extends Command
{
    protected $signature = 'zoopla:sync-properties';
    protected $description = 'Sync properties with Zoopla';

    public function handle()
    {
        $zooplaSettings = ZooplaSettings::first();
        $frequency = $zooplaSettings ? $zooplaSettings->sync_frequency : 'hourly';

        $this->info("Starting Zoopla property sync (Frequency: {$frequency})");

        try {
            SyncZooplaProperties::dispatch();
            $this->info('Zoopla property sync completed successfully');
        } catch (\Exception $e) {
            $this->error('Zoopla property sync failed: ' . $e->getMessage());
        }
    }
}