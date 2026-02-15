<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\PropertyTaxEstimatorService;

class PropertyTaxEstimator extends Component
{
    public $property;
    public $buyerType = 'home_mover';
    public $country;
    public $estimatedTax = null;
    public $showResults = false;

    protected $rules = [
        'buyerType' => 'required|in:first_time_buyer,home_mover,additional_property',
    ];

    public function mount($property)
    {
        $this->property = $property;
        $this->country = $property->country ?? 'UK';
    }

    public function calculateTax()
    {
        $this->validate();

        $taxEstimatorService = app(PropertyTaxEstimatorService::class);
        
        $options = [
            'buyer_type' => $this->buyerType,
        ];

        $this->estimatedTax = $taxEstimatorService->estimatePropertyTax(
            $this->property->price,
            $this->country,
            $options
        );

        $this->showResults = true;
    }

    public function resetCalculation()
    {
        $this->showResults = false;
        $this->estimatedTax = null;
        $this->buyerType = 'home_mover';
    }

    public function render()
    {
        return view('livewire.property-tax-estimator');
    }
}
