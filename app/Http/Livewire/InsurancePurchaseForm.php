<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Services\InsuranceProviderService;
use Livewire\Component;

class InsurancePurchaseForm extends Component
{
    public $property;
    public $quote;
    public $coverageAmount;
    public $deductible;
    public $term;

    protected $rules = [
        'coverageAmount' => 'required|numeric|min:1000',
        'deductible' => 'required|numeric|min:0',
        'term' => 'required|in:1,2,3,5',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function getQuote()
    {
        $this->validate();

        $insuranceService = new InsuranceProviderService();
        $this->quote = $insuranceService->getQuote([
            'property_value' => $this->property->price,
            'coverage_amount' => $this->coverageAmount,
            'deductible' => $this->deductible,
            'term' => $this->term,
        ]);
    }

    public function purchasePolicy()
    {
        $this->validate();

        $insuranceService = new InsuranceProviderService();
        $policy = $insuranceService->purchasePolicy([
            'property_id' => $this->property->id,
            'coverage_amount' => $this->coverageAmount,
            'deductible' => $this->deductible,
            'term' => $this->term,
            'premium' => $this->quote['premium'],
        ]);

        if ($policy) {
            $this->property->insurance_policy_id = $policy['id'];
            $this->property->save();
            session()->flash('message', 'Insurance policy purchased successfully.');
        } else {
            session()->flash('error', 'Failed to purchase insurance policy. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.insurance-purchase-form');
    }
}