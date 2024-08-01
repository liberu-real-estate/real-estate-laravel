<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\UtilityPaymentResource\Pages;
use App\Models\UtilityPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UtilityPaymentResource extends Resource
{
    protected static ?string $model = UtilityPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('energy_consumption_id')
                    ->relationship('energyConsumption', 'id')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('energyConsumption.property.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
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
            'index' => Pages\ListUtilityPayments::route('/'),
            'create' => Pages\CreateUtilityPayment::route('/create'),
            'edit' => Pages\EditUtilityPayment::route('/{record}/edit'),
        ];
    }
}