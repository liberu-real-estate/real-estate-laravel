<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RightMoveSettingsResource\Pages;
use App\Models\RightMoveSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RightMoveSettingsResource extends Resource
{
    protected static ?string $model = RightMoveSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('RightMove API Key'),
                Forms\Components\TextInput::make('base_uri')
                    ->required()
                    ->label('RightMove API Base URI'),
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
            'index' => Pages\ListRightMoveSettings::route('/'),
            'create' => Pages\CreateRightMoveSettings::route('/create'),
            'edit' => Pages\EditRightMoveSettings::route('/{record}/edit'),
        ];
    }
}