<?php

namespace App\Filament\Staff\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use App\Services\MortgageCalculatorService;
use App\Filament\Staff\Resources\MortgageCalculatorResource\Pages;

class MortgageCalculatorResource extends Resource
{
    protected static ?string $model = null;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Mortgage Calculator';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('property_price')
                    ->label('Property Price')
                    ->numeric()
                    ->required()
                    ->prefix('GBP'),
                Forms\Components\TextInput::make('loan_amount')
                    ->label('Loan Amount')
                    ->numeric()
                    ->required()
                    ->prefix('GBP'),
                Forms\Components\TextInput::make('interest_rate')
                    ->label('Interest Rate (%)')
                    ->numeric()
                    ->required()
                    ->suffix('%'),
                Forms\Components\TextInput::make('loan_term')
                    ->label('Loan Term (years)')
                    ->numeric()
                    ->integer()
                    ->required(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMortgageCalculators::route('/'),
            'create' => Pages\CreateMortgageCalculator::route('/create'),
            'edit' => Pages\EditMortgageCalculator::route('/{record}/edit'),
            'calculate' => Pages\CalculateMortgage::route('/calculate'),
        ];
    }
}