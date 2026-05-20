<?php

namespace App\Providers;

use Exception;
use Log;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\ValuationBooking;
use App\Http\Livewire\PropertyReviewForm;
use App\Http\Livewire\PropertyTaxEstimator;
use App\Http\Livewire\PropertyPreviewComponent;
use App\Http\Livewire\InvestmentAnalysisComponent;
use App\Http\Livewire\NeighborhoodReviewForm;
use App\Http\Livewire\PropertyValuationComponent;

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
            'property-tax-estimator' => PropertyTaxEstimator::class,
            'property-preview-component' => PropertyPreviewComponent::class,
            'investment-analysis-component' => InvestmentAnalysisComponent::class,
            'neighborhood-review-form' => NeighborhoodReviewForm::class,
            'property-valuation-component' => PropertyValuationComponent::class,
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
