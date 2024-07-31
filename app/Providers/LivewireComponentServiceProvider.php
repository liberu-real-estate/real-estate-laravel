<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\ValuationBooking;
use App\Http\Livewire\PropertyReviewForm;
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
            'valuation-booking' => ValuationBooking::class,
            'property-review-form' => PropertyReviewForm::class,
            // Add other Livewire components here
        ];
    
        foreach ($components as $alias => $class) {
            if (AppServiceProvider::isComponentEnabled($alias)) {
                try {
                    if (!class_exists($class)) {
                        throw new \Exception("Class {$class} does not exist");
                    }
                    Livewire::component($alias, $class);
                } catch (\Exception $e) {
                    // Log the error
                    \Log::error("Error registering Livewire component {$alias}: " . $e->getMessage());
                    // You might want to re-throw the exception or handle it differently based on your needs
                }
            }
        }
    }
}
