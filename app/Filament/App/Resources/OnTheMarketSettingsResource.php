<?php

namespace App\Filament\App\Resources;

use App\Models\OnTheMarketSettings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\App\Resources\OnTheMarketSettingsResource\Pages;

class OnTheMarketSettingsResource extends Resource
{
    protected static ?string $model = OnTheMarketSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->required()
                    ->label('API Key'),
                Forms\Components\TextInput::make('account_id')
                    ->required()
                    ->label('Account ID'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('api_key')->label('API Key'),
                Tables\Columns\TextColumn::make('account_id')->label('Account ID'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOnTheMarketSettings::route('/'),
            'create' => Pages\CreateOnTheMarketSettings::route('/create'),
            'edit' => Pages\EditOnTheMarketSettings::route('/{record}/edit'),
        ];
    }
}