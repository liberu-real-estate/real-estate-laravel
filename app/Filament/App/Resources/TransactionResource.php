<?php

namespace App\Filament\App\Resources;

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('buyer_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('seller_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_amount')
                    ->searchable()
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
