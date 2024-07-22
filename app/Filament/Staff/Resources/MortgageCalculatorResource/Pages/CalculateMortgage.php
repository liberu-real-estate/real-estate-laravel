<?php

namespace App\Filament\Staff\Resources\MortgageCalculatorResource\Pages;

use App\Filament\App\Resources\MortgageCalculatorResource;
use App\Services\MortgageCalculatorService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;

class CalculateMortgage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MortgageCalculatorResource::class;

    protected static string $view = 'filament.resources.mortgage-calculator-resource.pages.calculate-mortgage';

    public ?array $data = [];
    public ?array $results = null;

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(MortgageCalculatorResource::form($form)->getSchema())
            ->statePath('data');
    }

    public function calculate()
    {
        $data = $this->form->getState();
        $calculatorService = app(MortgageCalculatorService::class);
        $this->results = $calculatorService->calculateMortgage(
            $data['property_price'],
            $data['loan_amount'],
            $data['interest_rate'],
            $data['loan_term']
        );
    }

    public function getTitle(): string
    {
        return "Mortgage Calculator";
    }
}