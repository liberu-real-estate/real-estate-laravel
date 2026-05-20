<?php

namespace App\Filament\Staff\Resources\UtilityPayments;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\UtilityPayments\Pages\ListUtilityPayments;
use App\Filament\Staff\Resources\UtilityPayments\Pages\CreateUtilityPayment;
use App\Filament\Staff\Resources\UtilityPayments\Pages\EditUtilityPayment;
use App\Filament\Staff\Resources\UtilityPaymentResource\Pages;
use App\Models\UtilityPayment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UtilityPaymentResource extends Resource
{
    protected static ?string $model = UtilityPayment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('energy_consumption_id')
                    ->relationship('energyConsumption', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                DatePicker::make('payment_date')
                    ->required(),
                Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('energyConsumption.property.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListUtilityPayments::route('/'),
            'create' => CreateUtilityPayment::route('/create'),
            'edit' => EditUtilityPayment::route('/{record}/edit'),
        ];
    }
}