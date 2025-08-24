<?php

namespace App\Filament\Admin\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\ListSiteSettings;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\CreateSiteSettings;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\EditSiteSettings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\SiteSettingsService;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages;
use Intelrx\Sitesettings\Models\SiteSettings;

class SiteSettingsResource extends Resource
{
    protected static ?string $model = SiteSettings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')->label('Title')->required(),
                TextInput::make('value')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Title'),
                TextColumn::make('value')->label('Value'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteSettings::route('/'),
            'create' => CreateSiteSettings::route('/create'),
            'edit' => EditSiteSettings::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
    }

    protected function afterSave(): void
    {
        app(SiteSettingsService::class)->clear();
    }

    protected function afterDelete(): void
    {
        app(SiteSettingsService::class)->clear();
    }
}
