<?php

namespace App\Filament\Admin\Resources\RightMoveSettings;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\RightMoveSettings\Pages\ListRightMoveSettings;
use App\Filament\Admin\Resources\RightMoveSettings\Pages\CreateRightMoveSettings;
use App\Filament\Admin\Resources\RightMoveSettings\Pages\EditRightMoveSettings;
use App\Filament\Admin\Resources\RightMoveSettingsResource\Pages;
use App\Models\RightMoveSettings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RightMoveSettingsResource extends Resource
{
    protected static ?string $model = RightMoveSettings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_key')
                    ->required()
                    ->label('RightMove API Key'),
                TextInput::make('base_uri')
                    ->required()
                    ->label('RightMove API Base URI'),
                Select::make('sync_frequency')
                    ->options([
                        'hourly' => 'Hourly',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required()
                    ->label('Sync Frequency'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('api_key')->label('API Key'),
                TextColumn::make('base_uri')->label('Base URI'),
                TextColumn::make('sync_frequency')->label('Sync Frequency'),
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListRightMoveSettings::route('/'),
            'create' => CreateRightMoveSettings::route('/create'),
            'edit' => EditRightMoveSettings::route('/{record}/edit'),
        ];
    }
}