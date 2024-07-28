<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use App\Services\MenuService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SiteSettingsService::class, function ($app) {
            return new SiteSettingsService();
        });

        $this->app->singleton(MenuService::class, function ($app) {
            return new MenuService();
        });
    }

    public function boot()
    {
        Livewire::component('property-booking', PropertyBooking::class);
        Livewire::component('valuation-booking', \App\Http\Livewire\ValuationBooking::class);

        view()->composer('layouts.app', function ($view) {
            $view->with('menu', app(MenuService::class)->buildMenu());
        });
    }
}