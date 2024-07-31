<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Staff\Widgets\PropertyStatsOverview;
use App\Filament\Staff\Widgets\TopPerformingProperties;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerWidgets([
                PropertyStatsOverview::class,
                TopPerformingProperties::class,
            ]);
        });
    }
}