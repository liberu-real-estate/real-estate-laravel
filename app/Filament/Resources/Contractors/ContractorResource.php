<?php

namespace App\Filament\Resources\Contractors;

use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

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
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number'),
                Forms\Components\Textarea::make('address')
                    ->rows(5)
                    ->label('Address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('address')->limit(50),
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
