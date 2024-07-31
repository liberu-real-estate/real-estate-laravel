<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\MortgageCalculatorService;
use App\Services\CostOfMovingCalculatorService;
use App\Services\StampDutyCalculatorService;
use App\Services\RentalYieldCalculatorService;

class CalculatorsComponent extends Component
{
    public $calculatorType = 'mortgage';
    public $mortgageResult = null;
    public $costOfMovingResult = null;
    public $stampDutyResult = null;
    public $rentalYieldResult = null;

    // Mortgage calculator inputs
    public $propertyPrice;
    public $loanAmount;
    public $interestRate;
    public $loanTerm;

    // Cost of moving calculator inputs
    public $propertyValue;
    public $isFirstTimeBuyer;
    public $movingDistance;

    // Stamp duty calculator inputs
    public $purchasePrice;
    public $buyerType;

    // Rental yield calculator inputs
    public $rentalPropertyValue;
    public $annualRentalIncome;
    public $annualExpenses;

    protected $rules = [
        'propertyPrice' => 'required|numeric|min:0',
        'loanAmount' => 'required|numeric|min:0',
        'interestRate' => 'required|numeric|min:0',
        'loanTerm' => 'required|integer|min:1',
        'propertyValue' => 'required|numeric|min:0',
        'isFirstTimeBuyer' => 'required|boolean',
        'movingDistance' => 'required|numeric|min:0',
        'purchasePrice' => 'required|numeric|min:0',
        'buyerType' => 'required|in:first_time_buyer,home_mover,additional_property',
        'rentalPropertyValue' => 'required|numeric|min:0',
        'annualRentalIncome' => 'required|numeric|min:0',
        'annualExpenses' => 'required|numeric|min:0',
    ];

    public function render()
    {
        return view('livewire.calculators')->layout('layouts.app');
    }

    public function calculateMortgage()
    {
        $this->validate([
            'propertyPrice' => $this->rules['propertyPrice'],
            'loanAmount' => $this->rules['loanAmount'],
            'interestRate' => $this->rules['interestRate'],
            'loanTerm' => $this->rules['loanTerm'],
        ]);

        $mortgageCalculator = new MortgageCalculatorService();
        $this->mortgageResult = $mortgageCalculator->calculateMortgage(
            $this->propertyPrice,
            $this->loanAmount,
            $this->interestRate,
            $this->loanTerm
        );
    }

    public function calculateCostOfMoving()
    {
        $this->validate([
            'propertyValue' => $this->rules['propertyValue'],
            'isFirstTimeBuyer' => $this->rules['isFirstTimeBuyer'],
            'movingDistance' => $this->rules['movingDistance'],
        ]);

        $costOfMovingCalculator = new CostOfMovingCalculatorService();
        $this->costOfMovingResult = $costOfMovingCalculator->calculateCostOfMoving(
            $this->propertyValue,
            $this->isFirstTimeBuyer,
            $this->movingDistance
        );
    }

    public function calculateStampDuty()
    {
        $this->validate([
            'purchasePrice' => $this->rules['purchasePrice'],
            'buyerType' => $this->rules['buyerType'],
        ]);

        $stampDutyCalculator = new StampDutyCalculatorService();
        $this->stampDutyResult = $stampDutyCalculator->calculateStampDuty(
            $this->purchasePrice,
            $this->buyerType
        );
    }

    public function calculateRentalYield()
    {
        $this->validate([
            'rentalPropertyValue' => $this->rules['rentalPropertyValue'],
            'annualRentalIncome' => $this->rules['annualRentalIncome'],
            'annualExpenses' => $this->rules['annualExpenses'],
        ]);

        $rentalYieldCalculator = new RentalYieldCalculatorService();
        $this->rentalYieldResult = $rentalYieldCalculator->calculateRentalYield(
            $this->rentalPropertyValue,
            $this->annualRentalIncome,
            $this->annualExpenses
        );
    }

    public function setCalculatorType($type)
    {
        $this->calculatorType = $type;
        $this->resetCalculatorInputs();
    }

    private function resetCalculatorInputs()
    {
        $this->reset(['propertyPrice', 'loanAmount', 'interestRate', 'loanTerm', 'propertyValue', 'isFirstTimeBuyer', 'movingDistance', 'purchasePrice', 'buyerType', 'rentalPropertyValue', 'annualRentalIncome', 'annualExpenses']);
        $this->mortgageResult = null;
        $this->costOfMovingResult = null;
        $this->stampDutyResult = null;
        $this->rentalYieldResult = null;
    }
}