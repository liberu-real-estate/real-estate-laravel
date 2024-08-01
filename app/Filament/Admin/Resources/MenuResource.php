<?php

namespace App\Filament\Admin\Resources;

use App\Models\Menu;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use App\Filament\Admin\Resources\MenuResource\Pages;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->label('URL'),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Menu')
                    ->options(Menu::pluck('name', 'id'))
                    ->nullable(),
                Forms\Components\TextInput::make('order')
                    ->integer()
                    ->label('Order')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL'),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Menu'),
                Tables\Columns\TextColumn::make('order')
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}