<?php

namespace App\Filament\Resources;

use App\Models\KeyLocation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class KeyLocationResource extends Resource
{
/**
 * Resource class for managing key locations in the Filament admin panel.
 * Provides forms and tables for creating, editing, and listing key locations.
 */
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeyLocations::route('/'),
            'create' => Pages\CreateKeyLocation::route('/create'),
            'edit' => Pages\EditKeyLocation::route('/{record}/edit'),
        ];
    }
}
    /**
     * Provides the routes for key location resource pages.
     * @return array The array of page routes.
     */
            'create' => Pages\CreateKeyLocation::route('/create'),
            'edit' => Pages\EditKeyLocation::route('/{record}/edit'),
        ];
    }
}
    /**
     * Defines the table used for listing key locations.
     * @param Table $table The table builder instance.
     * @return Table The configured table instance.
     */
