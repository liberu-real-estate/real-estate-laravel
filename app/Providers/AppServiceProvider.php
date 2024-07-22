<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Team;

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
        //
    }
}