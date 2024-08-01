<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\EnergyConsumptionResource\Pages;
use App\Models\EnergyConsumption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnergyConsumptionResource extends Resource
{
    protected static ?string $model = EnergyConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                Forms\Components\DatePicker::make('consumption_date')
                    ->required(),
                Forms\Components\TextInput::make('electricity_usage')
                    ->required()
                    ->numeric()
                    ->suffix('kWh'),
                Forms\Components\TextInput::make('gas_usage')
                    ->required()
                    ->numeric()
                    ->suffix('m³'),
                Forms\Components\TextInput::make('water_usage')
                    ->required()
                    ->numeric()
                    ->suffix('m³'),
                Forms\Components\TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('consumption_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electricity_usage')
                    ->numeric()
                    ->suffix('kWh'),
                Tables\Columns\TextColumn::make('gas_usage')
                    ->numeric()
                    ->suffix('m³'),
                Tables\Columns\TextColumn::make('water_usage')
                    ->numeric()
                    ->suffix('m³'),
                Tables\Columns\TextColumn::make('total_cost')
                    ->money('usd')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnergyConsumptions::route('/'),
            'create' => Pages\CreateEnergyConsumption::route('/create'),
            'edit' => Pages\EditEnergyConsumption::route('/{record}/edit'),
        ];
    }
}