<?php

namespace App\Filament\App\Resources;

use App\Models\KeyLocation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;


class KeyLocationResource extends Resource
{
    protected static ?string $model = KeyLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location_name')
                    ->required()
                    ->label('Location Name'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location_name')->label('Location Name'),
                Tables\Columns\TextColumn::make('address')->label('Address'),
            ])
            ->filters([
                //
            ]);
    }

    // public static function getPages(): array
    // {
    //     return [
    //         'index' => Pages\ListKeyLocations::route('/'),
    //         'create' => Pages\CreateKeyLocation::route('/create'),
    //         'edit' => Pages\EditKeyLocation::route('/{record}/edit'),
    //     ];
    // }
}
