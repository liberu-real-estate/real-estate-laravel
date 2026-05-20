<?php

namespace App\Filament\Admin\Resources\ZooplaSettings;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\ZooplaSettings\Pages\ListZooplaSettings;
use App\Filament\Admin\Resources\ZooplaSettings\Pages\CreateZooplaSettings;
use App\Filament\Admin\Resources\ZooplaSettings\Pages\EditZooplaSettings;
use App\Models\ZooplaSettings;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\ZooplaSettingsResource\Pages;

class ZooplaSettingsResource extends Resource
{
    protected static ?string $model = ZooplaSettings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Zoopla Settings';

    protected static ?string $modelLabel = 'Zoopla Setting';

    protected static ?string $pluralModelLabel = 'Zoopla Settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_key')
                    ->required()
                    ->label('Zoopla API Key')
                    ->password(),
                TextInput::make('base_uri')
                    ->required()
                    ->label('Zoopla API Base URI')
                    ->url(),
                TextInput::make('feed_id')
                    ->nullable()
                    ->label('Feed ID'),
                Select::make('sync_frequency')
                    ->options([
                        'hourly' => 'Hourly',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required()
                    ->label('Sync Frequency'),
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
                TextColumn::make('feed_id')->label('Feed ID'),
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
            'index' => ListZooplaSettings::route('/'),
            'create' => CreateZooplaSettings::route('/create'),
            'edit' => EditZooplaSettings::route('/{record}/edit'),
        ];
    }
}
