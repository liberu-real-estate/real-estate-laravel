<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\RightMoveService;
use App\Services\OnTheMarketService;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync properties with RightMove every hour
        $schedule->call(function () {
            $rightMoveService = app(RightMoveService::class);
            $rightMoveService->syncAllProperties();
        })->hourly();
    
        // Sync properties with OnTheMarket
        $schedule->call(function () {
            $onTheMarketService = app(OnTheMarketService::class);
            $onTheMarketService->syncAllProperties();
        })->when(function () {
            $frequency = config('services.onthemarket.sync_frequency', 'hourly');
            return $frequency === 'hourly';
        })->hourly();
    
        $schedule->call(function () {
            $onTheMarketService = app(OnTheMarketService::class);
            $onTheMarketService->syncAllProperties();
        })->when(function () {
            $frequency = config('services.onthemarket.sync_frequency', 'hourly');
            return $frequency === 'daily';
        })->daily();
    
        $schedule->call(function () {
            $onTheMarketService = app(OnTheMarketService::class);
            $onTheMarketService->syncAllProperties();
        })->when(function () {
            $frequency = config('services.onthemarket.sync_frequency', 'hourly');
            return $frequency === 'weekly';
        })->weekly();
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
