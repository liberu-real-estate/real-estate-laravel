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
                Forms\Components\TextInput::make('contact_number')
                    ->tel()
                    ->label('Contact Number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('contact_number')->label('Contact Number'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }
}
