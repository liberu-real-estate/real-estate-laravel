<?php

namespace App\Filament\App\Resources;

use App\Models\OnTheMarketSettings;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\App\Resources\OnTheMarketSettingsResource\Pages;

class OnTheMarketSettingsResource extends Resource
{
    protected static ?string $model = OnTheMarketSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Add form fields here
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Add table columns here
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

    protected static ?string $navigationLabel = 'On The Market Settings';

    protected static ?string $modelLabel = 'On The Market Setting';

    protected static ?string $pluralModelLabel = 'On The Market Settings';

    protected static ?string $navigationGroup = 'Settings';
}