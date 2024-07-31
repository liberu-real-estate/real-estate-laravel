<?php

namespace App\Filament\Admin\Resources;

use App\Models\ZooplaSettings;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\ZooplaSettingsResource\Pages;

class ZooplaSettingsResource extends Resource
{
    protected static ?string $model = ZooplaSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Zoopla Settings';

    protected static ?string $modelLabel = 'Zoopla Setting';

    protected static ?string $pluralModelLabel = 'Zoopla Settings';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('Zoopla API Key'),
                Forms\Components\TextInput::make('base_uri')
                    ->required()
                    ->label('Zoopla API Base URI'),
                Forms\Components\Select::make('sync_frequency')
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
                Tables\Columns\TextColumn::make('api_key')->label('API Key'),
                Tables\Columns\TextColumn::make('base_uri')->label('Base URI'),
                Tables\Columns\TextColumn::make('sync_frequency')->label('Sync Frequency'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListZooplaSettings::route('/'),
            'create' => Pages\CreateZooplaSettings::route('/create'),
            'edit' => Pages\EditZooplaSettings::route('/{record}/edit'),
        ];
    }
}
