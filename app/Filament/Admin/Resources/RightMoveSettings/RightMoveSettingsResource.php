<?php

namespace App\Filament\Admin\Resources\RightMoveSettings;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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

    protected static ?string $navigationLabel = 'RightMove Settings';

    protected static ?string $modelLabel = 'RightMove Setting';

    protected static ?string $pluralModelLabel = 'RightMove Settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_key')
                    ->required()
                    ->label('RightMove API Key')
                    ->password(),
                TextInput::make('base_uri')
                    ->nullable()
                    ->label('RightMove API Base URI')
                    ->url(),
                Select::make('channel')
                    ->options([
                        'sales' => 'Sales',
                        'lettings' => 'Lettings',
                    ])
                    ->required()
                    ->label('Channel'),
                Select::make('feed_type')
                    ->options([
                        'full' => 'Full',
                        'incremental' => 'Incremental',
                    ])
                    ->required()
                    ->label('Feed Type'),
                Select::make('sync_frequency')
                    ->options([
                        'hourly' => 'Hourly',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required()
                    ->label('Sync Frequency'),
                TextInput::make('feed_url')
                    ->nullable()
                    ->label('Feed URL')
                    ->url(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('base_uri')->label('Base URI'),
                TextColumn::make('channel')->label('Channel'),
                TextColumn::make('feed_type')->label('Feed Type'),
                TextColumn::make('sync_frequency')->label('Sync Frequency'),
                ToggleColumn::make('is_active')->label('Active'),
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