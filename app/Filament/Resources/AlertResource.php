<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('property_type')
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('min_price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('max_price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('min_bedrooms')
                    ->numeric(),
                Forms\Components\TextInput::make('max_bedrooms')
                    ->numeric(),
                Forms\Components\TextInput::make('location')
                    ->required(),
                Forms\Components\Select::make('notification_frequency')
                    ->options([
                        'immediately' => 'Immediately',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('property_type'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('notification_frequency'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAlerts::route('/'),
            'create' => Pages\CreateAlert::route('/create'),
            'edit' => Pages\EditAlert::route('/{record}/edit'),
        ];
    }
}