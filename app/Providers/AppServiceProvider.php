<?php

namespace App\Providers;

use App\Models\ComponentSettings;
use App\Services\SiteSettingsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\QueryException;

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
        // Remove the registerLivewireComponents method call
    }

    public static function isComponentEnabled($componentName)
    {
        try {
            $setting = ComponentSettings::where('component_name', $componentName)->first();
            return $setting ? $setting->is_enabled : true;
        } catch (QueryException $e) {
            // If the table doesn't exist, return true as default
            if ($e->getCode() == "42S02") {
                return true;
            }
            throw $e;
        }
    }
}