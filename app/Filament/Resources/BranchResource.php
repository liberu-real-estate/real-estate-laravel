<?php

namespace App\Filament\Resources;

use App\Models\Branch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Address'),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->label('Phone Number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->label('Address'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getLabel(): string
    {
        return 'Branch';
    }

    public static function getPluralLabel(): string
    {
        return 'Branches';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-office-building';
    }
}
