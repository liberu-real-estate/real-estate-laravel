<?php

namespace App\Filament\Staff\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Staff\Resources\TransactionResource\Pages;
use App\Filament\Staff\Resources\TransactionResource\RelationManagers;
use App\Filament\Staff\Resources\TransactionResource\Pages\EditTransaction;
use App\Filament\Staff\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Staff\Resources\TransactionResource\Pages\CreateTransaction;
use App\Services\TransactionService;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                BelongsToSelect::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->required(),
                BelongsToSelect::make('seller_id')
                    ->relationship('seller', 'name')
                    ->required(),
                DatePicker::make('transaction_date')
                    ->label('Transaction Date')
                    ->required(),
                TextInput::make('transaction_amount')
                    ->type('number')
                    ->label('Transaction Amount')
                    ->required(),
                Select::make('status')
                    ->options([
                        Transaction::STATUS_PENDING => 'Pending',
                        Transaction::STATUS_IN_PROGRESS => 'In Progress',
                        Transaction::STATUS_COMPLETED => 'Completed',
                        Transaction::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->required(),
                TextInput::make('commission_amount')
                    ->type('number')
                    ->label('Commission Amount')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('buyer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('seller.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_amount')
                    ->money('gbp')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('commission_amount')
                    ->money('gbp')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generateDocument')
                    ->label('Generate Document')
                    ->action(function (Transaction $record, TransactionService $transactionService) {
                        $document = $transactionService->generateContractualDocument($record);
                        // You might want to add a notification or redirect here
                    }),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
