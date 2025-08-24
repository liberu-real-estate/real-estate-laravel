<?php

namespace App\Filament\Staff\Resources\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('room_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('size')
                    ->required()
                    ->numeric()
                    ->suffix('sq ft'),
                TextInput::make('rent')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_available')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room_number'),
                TextColumn::make('size')
                    ->suffix(' sq ft'),
                TextColumn::make('rent')
                    ->money('USD'),
                IconColumn::make('is_available')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
