<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BooминSettingsResource\Pages;
use App\Models\BooминSettings;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BooминSettingsResource extends Resource
{
    protected static ?string $model = BooминSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('Boomin API Key'),
                Forms\Components\TextInput::make('base_uri')
                    ->required()
                    ->label('Boomin API Base URI'),
                Forms\Components\Select::make('sync_frequency')
                    ->options([
                        1 => 'Every hour',
                        2 => 'Every 2 hours',
                        4 => 'Every 4 hours',
                        6 => 'Every 6 hours',
                        12 => 'Every 12 hours',
                        24 => 'Daily',
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
            'index' => Pages\ListBooминSettings::route('/'),
            'create' => Pages\CreateBooминSettings::route('/create'),
            'edit' => Pages\EditBooминSettings::route('/{record}/edit'),
        ];
    }
}