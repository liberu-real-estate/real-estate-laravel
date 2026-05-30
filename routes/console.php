<?php

use App\Models\Neighborhood;
use App\Models\RightMoveSettings;
use App\Models\ZooplaSettings;
use App\Services\NeighborhoodDataService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::command('rightmove:sync-properties')
    ->hourly()
    ->when(fn () => RightMoveSettings::active()->first()?->sync_frequency === 'hourly' ?? true)
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('RightMove hourly sync failed'))
    ->onSuccess(fn () => Log::info('RightMove hourly sync completed'));

Schedule::command('rightmove:sync-properties')
    ->daily()
    ->when(fn () => RightMoveSettings::active()->first()?->sync_frequency === 'daily' ?? false)
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('RightMove daily sync failed'))
    ->onSuccess(fn () => Log::info('RightMove daily sync completed'));

Schedule::command('onthemarket:sync-properties')
    ->hourly()
    ->when(fn () => config('services.onthemarket.sync_frequency', 'hourly') === 'hourly')
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('OnTheMarket hourly sync failed'))
    ->onSuccess(fn () => Log::info('OnTheMarket hourly sync completed'));

Schedule::command('onthemarket:sync-properties')
    ->daily()
    ->when(fn () => config('services.onthemarket.sync_frequency', 'hourly') === 'daily')
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('OnTheMarket daily sync failed'))
    ->onSuccess(fn () => Log::info('OnTheMarket daily sync completed'));

Schedule::command('onthemarket:sync-properties')
    ->weekly()
    ->when(fn () => config('services.onthemarket.sync_frequency', 'hourly') === 'weekly')
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('OnTheMarket weekly sync failed'))
    ->onSuccess(fn () => Log::info('OnTheMarket weekly sync completed'));

Schedule::command('zoopla:sync-properties')
    ->hourly()
    ->when(fn () => ZooplaSettings::first()?->sync_frequency === 'hourly' ?? true)
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('Zoopla hourly sync failed'))
    ->onSuccess(fn () => Log::info('Zoopla hourly sync completed'));

Schedule::command('zoopla:sync-properties')
    ->daily()
    ->when(fn () => ZooplaSettings::first()?->sync_frequency === 'daily' ?? false)
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('Zoopla daily sync failed'))
    ->onSuccess(fn () => Log::info('Zoopla daily sync completed'));

Schedule::command('zoopla:sync-properties')
    ->weekly()
    ->when(fn () => ZooplaSettings::first()?->sync_frequency === 'weekly' ?? false)
    ->withoutOverlapping()
    ->onFailure(fn () => Log::error('Zoopla weekly sync failed'))
    ->onSuccess(fn () => Log::info('Zoopla weekly sync completed'));

Schedule::job(new \App\Jobs\LeaseRenewalReminder)->daily();

Schedule::call(function () {
    $neighborhoods = Neighborhood::all();
    $service = app(NeighborhoodDataService::class);
    foreach ($neighborhoods as $neighborhood) {
        $property = $neighborhood->properties()->first();
        if ($property && ($freshData = $service->getNeighborhoodData($property->postal_code))) {
            $neighborhood->update([
                'median_income' => $freshData['median_income'],
                'population' => $freshData['population'],
                'walk_score' => $freshData['walk_score'],
                'transit_score' => $freshData['transit_score'],
                'last_updated' => now(),
            ]);
        }
    }
})->daily();
