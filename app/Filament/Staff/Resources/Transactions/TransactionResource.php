<?php

namespace App\Filament\Staff\Resources\Transactions;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Transactions\Pages\ViewTransaction;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Staff\Resources\TransactionResource\Pages;
use App\Filament\Staff\Resources\TransactionResource\RelationManagers;
use App\Filament\Staff\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Staff\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Staff\Resources\Transactions\Pages\CreateTransaction;
use App\Services\TransactionService;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->required(),
                Select::make('seller_id')
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
            ->recordActions([
                EditAction::make(),
                Action::make('generateDocument')
                    ->label('Generate Document')
                    ->action(function (Transaction $record, TransactionService $transactionService) {
                        $document = $transactionService->generateContractualDocument($record);
                        // You might want to add a notification or redirect here
                    }),
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
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
            'view' => ViewTransaction::route('/{record}'),
        ];
    }
}
