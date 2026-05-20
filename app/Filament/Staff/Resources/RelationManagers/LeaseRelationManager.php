<?php

namespace App\Filament\Staff\Resources\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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

class LeaseRelationManager extends RelationManager
{
    protected static string $relationship = 'leases';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('rent_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('terms')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->date(),
                TextColumn::make('end_date')
                    ->date(),
                TextColumn::make('rent_amount')
                    ->money(),
                TextColumn::make('terms')
                    ->limit(50),
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