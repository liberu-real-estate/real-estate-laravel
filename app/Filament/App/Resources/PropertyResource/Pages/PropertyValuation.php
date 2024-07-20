<?php

namespace App\Filament\App\Resources\PropertyResource\Pages;

use App\Filament\App\Resources\PropertyResource;
use App\Models\Property;
use App\Services\PropertyValuationService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class PropertyValuation extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PropertyResource::class;

    protected static string $view = 'filament.resources.property-resource.pages.property-valuation';

    public ?array $data = [];

    public Property $property;

    public function mount(Property $record)
    {
        $this->property = $record;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('market_trend_factor')
                    ->label('Market Trend Factor')
                    ->numeric()
                    ->default(1)
                    ->required(),
                // Add more inputs for additional valuation factors as needed
            ])
            ->statePath('data');
    }

    public function valuate()
    {
        $valuationService = app(PropertyValuationService::class);
        $estimatedValue = $valuationService->calculateValuation($this->property, $this->form->getState());

        $this->data['estimated_value'] = $estimatedValue;
    }

    public function getTitle(): string
    {
        return "Valuate: {$this->property->title}";
    }
}