<?php

namespace App\Filament\Staff\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Staff\Resources\KeyLocationResource\Pages\ListKeyLocations;
use App\Filament\Staff\Resources\KeyLocationResource\Pages\CreateKeyLocation;
use App\Filament\Staff\Resources\KeyLocationResource\Pages\EditKeyLocation;
use App\Models\KeyLocation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\KeyLocationResource\Pages;

class KeyLocationResource extends Resource
{
    protected static ?string $model = KeyLocation::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('location_name')
                    ->required()
                    ->label('Location Name'),
                TextInput::make('address')
                    ->required()
                    ->label('Address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('location_name')->label('Location Name'),
                TextColumn::make('address')->label('Address'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKeyLocations::route('/'),
            'create' => CreateKeyLocation::route('/create'),
            'edit' => EditKeyLocation::route('/{record}/edit'),
        ];
    }
}
