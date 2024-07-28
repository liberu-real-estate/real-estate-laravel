<?php

namespace App\Providers;

use App\Models\ComponentSettings;
use App\Services\SiteSettingsService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;
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
        $this->registerLivewireComponents();
    }

    private function registerLivewireComponents()
    {
        $components = [
            'property-booking' => PropertyBooking::class,
            'valuation-booking' => \App\Http\Livewire\ValuationBooking::class,
            // Add other Livewire components here
        ];

        foreach ($components as $name => $class) {
            if ($this->isComponentEnabled($name)) {
                Livewire::component($name, $class);
            }
        }
    }

    private function isComponentEnabled($componentName)
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