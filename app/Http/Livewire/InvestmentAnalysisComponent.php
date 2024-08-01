<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\AIInvestmentAnalysisService;
use App\Services\InvestmentAnalysisService;

class InvestmentAnalysisComponent extends Component
{
    public $property;
    public $scenarios = [];
    public $currentScenario = 0;
    public $analysisResults = [];
    public $aiAnalysisResult;

    protected $rules = [
        'scenarios.*.purchasePrice' => 'required|numeric|min:0',
        'scenarios.*.annualRentalIncome' => 'required|numeric|min:0',
        'scenarios.*.annualExpenses' => 'required|numeric|min:0',
        'scenarios.*.appreciationRate' => 'required|numeric|min:0|max:100',
        'scenarios.*.holdingPeriod' => 'required|integer|min:1',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->addScenario();
    }

    public function render()
    {
        return view('livewire.investment-analysis')->layout('layouts.app');
    }

    public function addScenario()
    {
        $this->scenarios[] = [
            'purchasePrice' => $this->property->price,
            'annualRentalIncome' => $this->property->annual_rental_income ?? 0,
            'annualExpenses' => $this->property->annual_expenses ?? 0,
            'appreciationRate' => 3,
            'holdingPeriod' => 5,
        ];
        $this->currentScenario = count($this->scenarios) - 1;
    }

    public function removeScenario($index)
    {
        unset($this->scenarios[$index]);
        $this->scenarios = array_values($this->scenarios);
        $this->currentScenario = min($this->currentScenario, count($this->scenarios) - 1);
    }

    public function analyzeScenarios()
    {
        $this->validate();

        $analysisService = new InvestmentAnalysisService();
        $this->analysisResults = [];

        foreach ($this->scenarios as $scenario) {
            $this->analysisResults[] = $analysisService->analyze(
                $scenario['purchasePrice'],
                $scenario['annualRentalIncome'],
                $scenario['annualExpenses'],
                $scenario['appreciationRate'],
                $scenario['holdingPeriod']
            );
        }

        $aiAnalysisService = app(AIInvestmentAnalysisService::class);
        $this->aiAnalysisResult = $aiAnalysisService->analyzeInvestment($this->property);
    }
}