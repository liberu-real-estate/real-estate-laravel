<?php

namespace App\Filament\Admin\Resources\Menus;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Menus\Pages\ListMenus;
use App\Filament\Admin\Resources\Menus\Pages\CreateMenu;
use App\Filament\Admin\Resources\Menus\Pages\EditMenu;
use App\Models\Menu;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use App\Filament\Admin\Resources\MenuResource\Pages;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bars-4';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Name'),
                TextInput::make('url')
                    ->required()
                    ->label('URL'),
                Select::make('parent_id')
                    ->label('Parent Menu')
                    ->options(Menu::pluck('name', 'id'))
                    ->nullable(),
                TextInput::make('order')
                    ->integer()
                    ->label('Order')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                TextColumn::make('url')
                    ->label('URL'),
                TextColumn::make('parent.name')
                    ->label('Parent Menu'),
                TextColumn::make('order')
                    ->sortable()
                    ->label('Order'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getLabel(): string
    {
        return 'Menu';
    }

    public static function getPluralLabel(): string
    {
        return 'Menus';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}