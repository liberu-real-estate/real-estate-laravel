<?php

namespace App\Filament\Resources;

use App\Models\Buyer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;

class BuyerResource extends Resource
{
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
