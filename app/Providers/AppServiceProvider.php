<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use App\Services\ComponentSettingsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SiteSettingsService::class, function ($app) {
            return new SiteSettingsService();
        });

        $this->app->singleton(ComponentSettingsService::class, function ($app) {
            return new ComponentSettingsService();
        });
    }

    public function boot()
    {
        // This method is now empty
    }

    public static function isComponentEnabled($componentName)
    {
        return app(ComponentSettingsService::class)->isEnabled($componentName);
    }
}