<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;
use App\Providers\AppServiceProvider;

class LivewireComponentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerLivewireComponents();
    }

    private function registerLivewireComponents()
    {
        $components = [
            'property-booking' => PropertyBooking::class,
            // Add other Livewire components here
        ];

        foreach ($components as $alias => $class) {
            if (AppServiceProvider::isComponentEnabled($alias)) {
                Livewire::component($alias, $class);
            }
        }
    }
}