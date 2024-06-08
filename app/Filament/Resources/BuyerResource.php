<?php

namespace App\Filament\Resources;

use App\Models\Buyer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BuyerResource extends Resource
{
/**
 * BuyerResource class defines the form and table schemas for managing `Buyer` entities within the Filament admin panel.
 */
    protected static ?string $model = Buyer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                // Add additional fields as necessary
            ]);
    }

    public static function table(Table $table): Table
    /**
     * Constructs the form schema for creating or editing a `Buyer` entity.
     * 
     * @param Form $form The form object to be modified.
     * @return Form The modified form object with a schema for `Buyer`.
     */
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                // Add additional columns as necessary
            ])
            ->filters([
                //
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'User Management';
    }
}
    /**
     * Constructs the table schema for listing `Buyer` entities.
     * 
     * @param Table $table The table object to be modified.
     * @return Table The modified table object with columns and filters for `Buyer`.
     */
