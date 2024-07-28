<?php

namespace App\Providers;

use App\Models\ComponentSettings;
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
        $setting = ComponentSettings::where('component_name', $componentName)->first();
        return $setting ? $setting->is_enabled : true;
    }
}