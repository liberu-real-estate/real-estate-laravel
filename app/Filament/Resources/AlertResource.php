<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\AlertResource\Pages\ListAlerts;
use App\Filament\Resources\AlertResource\Pages\CreateAlert;
use App\Filament\Resources\AlertResource\Pages\EditAlert;
use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('property_type')
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                    ])
                    ->required(),
                TextInput::make('min_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('max_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('min_bedrooms')
                    ->numeric(),
                TextInput::make('max_bedrooms')
                    ->numeric(),
                TextInput::make('location')
                    ->required(),
                Select::make('notification_frequency')
                    ->options([
                        'immediately' => 'Immediately',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required(),
                MultiSelect::make('alert_types')
                    ->options([
                        'price_change' => 'Price Change',
                        'new_listing' => 'New Listing',
                        'open_house' => 'Open House',
                        'status_change' => 'Status Change',
                    ])
                    ->required(),
                TextInput::make('price_change_threshold')
                    ->numeric()
                    ->suffix('%')
                    ->helperText('Percentage change to trigger alert'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('property_type'),
                TextColumn::make('location'),
                TextColumn::make('notification_frequency'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
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
            'index' => ListAlerts::route('/'),
            'create' => CreateAlert::route('/create'),
            'edit' => EditAlert::route('/{record}/edit'),
        ];
    }
}