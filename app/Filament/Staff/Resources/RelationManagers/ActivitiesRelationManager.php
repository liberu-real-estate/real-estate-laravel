<?php

namespace App\Filament\Staff\Resources\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                DateTimePicker::make('scheduled_at')
                    ->required(),
                DateTimePicker::make('completed_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type'),
                TextColumn::make('description'),
                TextColumn::make('scheduled_at')
                    ->dateTime(),
                TextColumn::make('completed_at')
                    ->dateTime(),
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