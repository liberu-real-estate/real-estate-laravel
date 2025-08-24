<?php

namespace App\Filament\Admin\Resources\OnTheMarketSettings;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\OnTheMarketSettings\Pages\ListOnTheMarketSettings;
use App\Filament\Admin\Resources\OnTheMarketSettings\Pages\CreateOnTheMarketSettings;
use App\Filament\Admin\Resources\OnTheMarketSettings\Pages\EditOnTheMarketSettings;
use App\Models\OnTheMarketSettings;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\OnTheMarketSettingsResource\Pages;

class OnTheMarketSettingsResource extends Resource
{
    protected static ?string $model = OnTheMarketSettings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'On The Market Settings';

    protected static ?string $modelLabel = 'On The Market Setting';

    protected static ?string $pluralModelLabel = 'On The Market Settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListOnTheMarketSettings::route('/'),
            'create' => CreateOnTheMarketSettings::route('/create'),
            'edit' => EditOnTheMarketSettings::route('/{record}/edit'),
        ];
    }
}
