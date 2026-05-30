<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\PropertyBooking;
use App\Livewire\ValuationBooking;
use App\Livewire\PropertyReviewForm;
use App\Livewire\PropertyTaxEstimator;
use App\Livewire\PropertyPreviewComponent;
use App\Livewire\InvestmentAnalysisComponent;
use App\Livewire\NeighborhoodReviewForm;
use App\Livewire\PropertyValuationComponent;

class LivewireComponentServiceProvider extends ServiceProvider
{
    public function boot(): void
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
                Log::error("Error registering Livewire component {$alias}: " . $e->getMessage());
            }
        }
    }
}
