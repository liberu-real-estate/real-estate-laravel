<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
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
    }

    public function boot()
    {
        Livewire::component('property-booking', PropertyBooking::class);
    }
}