<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\SiteSettingsService;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages;
use Intelrx\Sitesettings\Models\SiteSettings;

class SiteSettingsResource extends Resource
{
    protected static ?string $model = SiteSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')->label('Title')->required(),
                Forms\Components\TextInput::make('value')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Title'),
                Tables\Columns\TextColumn::make('value')->label('Value'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSettings::route('/create'),
            'edit' => Pages\EditSiteSettings::route('/{record}/edit'),
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
