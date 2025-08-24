<?php

namespace App\Providers;

use Exception;
use Log;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\ValuationBooking;
use App\Http\Livewire\PropertyReviewForm;

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
            try {
                if (class_exists($class)) {
                    Livewire::component($alias, $class);
                }
            } catch (Exception $e) {
                // Log the error
                Log::error("Error registering Livewire component {$alias}: " . $e->getMessage());
            }
        }
    }
}