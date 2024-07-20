<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OnTheMarketIntegrationResource\Pages;
use App\Models\OnTheMarketIntegration;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OnTheMarketIntegrationResource extends Resource
{
    protected static ?string $model = OnTheMarketIntegration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('OnTheMarket API Key'),
                Forms\Components\TextInput::make('api_endpoint')
                    ->required()
                    ->label('OnTheMarket API Endpoint'),
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
                Tables\Columns\TextColumn::make('api_endpoint')->label('API Endpoint'),
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
            'index' => Pages\ListOnTheMarketIntegrations::route('/'),
            'create' => Pages\CreateOnTheMarketIntegration::route('/create'),
            'edit' => Pages\EditOnTheMarketIntegration::route('/{record}/edit'),
        ];
    }
}