<?php

namespace App\Filament\Tenant\Resources\Payments;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use App\Filament\Tenant\Resources\Payments\Pages\ListPayments;
use App\Filament\Tenant\Resources\Payments\Pages\CreatePayment;
use App\Filament\Tenant\Resources\Payments\Pages\ViewPayment;
use App\Models\Payment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Tenant\Resources\PaymentResource\Pages;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payments';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                DatePicker::make('payment_date')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                Select::make('payment_method')
                    ->required()
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'paypal' => 'PayPal',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('payment_method'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view' => ViewPayment::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tenant_id', auth()->id());
    }
}
