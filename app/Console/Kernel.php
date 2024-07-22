<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SyncRightMoveProperties;
use App\Jobs\SyncOnTheMarketProperties;
use App\Jobs\SyncZooplaProperties;
use App\Models\ZooplaSettings;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule): void
    {

        // Sync properties with RightMove every hour
        $schedule->job(new SyncRightMoveProperties)->hourly();

        // Sync properties with OnTheMarket
        $schedule->job(new SyncOnTheMarketProperties)
            ->when(function () {
                $frequency = config('services.onthemarket.sync_frequency', 'hourly');
                return $frequency === 'hourly';
            })
            ->hourly();

        $schedule->job(new SyncOnTheMarketProperties)
            ->when(function () {
                $frequency = config('services.onthemarket.sync_frequency', 'hourly');
                return $frequency === 'daily';
            })
            ->daily();

        $schedule->job(new SyncOnTheMarketProperties)
            ->when(function () {
                $frequency = config('services.onthemarket.sync_frequency', 'hourly');
                return $frequency === 'weekly';
            })
            ->weekly();

        // Sync properties with Zoopla
        $schedule->job(new SyncZooplaProperties)
            ->when(function () {
                $zooplaSettings = ZooplaSettings::first();
                $frequency = $zooplaSettings ? $zooplaSettings->sync_frequency : 'hourly';
                return $frequency === 'hourly';
            })
            ->hourly();

        $schedule->job(new SyncZooplaProperties)
            ->when(function () {
                $zooplaSettings = ZooplaSettings::first();
                $frequency = $zooplaSettings ? $zooplaSettings->sync_frequency : 'hourly';
                return $frequency === 'daily';
            })
            ->daily();

        $schedule->job(new SyncZooplaProperties)
            ->when(function () {
                $zooplaSettings = ZooplaSettings::first();
                $frequency = $zooplaSettings ? $zooplaSettings->sync_frequency : 'hourly';
                return $frequency === 'weekly';
            })
            ->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
