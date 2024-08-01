<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\AIInvestmentAnalysisService;
use App\Services\InvestmentAnalysisService;

class InvestmentAnalysisComponent extends Component
{
    public $property;
    public $purchasePrice;
    public $annualRentalIncome;
    public $annualExpenses;
    public $appreciationRate;
    public $holdingPeriod;
    public $analysisResult;
    public $aiAnalysisResult;

    protected $rules = [
        'purchasePrice' => 'required|numeric|min:0',
        'annualRentalIncome' => 'required|numeric|min:0',
        'annualExpenses' => 'required|numeric|min:0',
        'appreciationRate' => 'required|numeric|min:0|max:100',
        'holdingPeriod' => 'required|integer|min:1',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->purchasePrice = $property->price;
        $this->annualRentalIncome = $property->annual_rental_income ?? 0;
        $this->annualExpenses = $property->annual_expenses ?? 0;
        $this->appreciationRate = 3; // Default 3% appreciation rate
        $this->holdingPeriod = 5; // Default 5 years holding period
    }

    public function render()
    {
        return view('livewire.investment-analysis')->layout('layouts.app');
    }

    public function analyze()
    {
        $this->validate();

        $analysisService = new InvestmentAnalysisService();
        $this->analysisResult = $analysisService->analyze(
            $this->purchasePrice,
            $this->annualRentalIncome,
            $this->annualExpenses,
            $this->appreciationRate,
            $this->holdingPeriod
        );

        $aiAnalysisService = app(AIInvestmentAnalysisService::class);
        $this->aiAnalysisResult = $aiAnalysisService->analyzeInvestment($this->property);
    }
}