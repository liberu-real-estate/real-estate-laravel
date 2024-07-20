<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\RightMoveSettingsResource\Pages;
use App\Models\RightMoveSettings;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RightMoveSettingsResource extends Resource
{
    protected static ?string $model = RightMoveSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('API Key'),
                Forms\Components\TextInput::make('network_id')
                    ->required()
                    ->label('Network ID'),
                Forms\Components\TextInput::make('branch_id')
                    ->required()
                    ->label('Branch ID'),
                Forms\Components\TextInput::make('sync_interval')
                    ->required()
                    ->numeric()
                    ->label('Sync Interval (minutes)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('api_key')
                    ->label('API Key'),
                Tables\Columns\TextColumn::make('network_id')
                    ->label('Network ID'),
                Tables\Columns\TextColumn::make('branch_id')
                    ->label('Branch ID'),
                Tables\Columns\TextColumn::make('sync_interval')
                    ->label('Sync Interval (minutes)'),
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