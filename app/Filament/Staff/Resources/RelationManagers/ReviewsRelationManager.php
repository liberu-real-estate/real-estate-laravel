<?php

namespace App\Filament\Staff\Resources\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->min(1)
                    ->max(5),
                Textarea::make('comment')
                    ->required()
                    ->maxLength(1000),
                Toggle::make('approved')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User'),
                TextColumn::make('rating'),
                TextColumn::make('comment')
                    ->limit(50),
                IconColumn::make('approved')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('approved')
                    ->options([
                        '1' => 'Approved',
                        '0' => 'Not Approved',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->action(fn ($record) => $record->update(['approved' => true]))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->approved),
                Action::make('unapprove')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn ($record) => $record->update(['approved' => false]))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => !$record->approved),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('approve')
                        ->action(fn ($records) => $records->each->update(['approved' => true]))
                        ->requiresConfirmation(),
                    BulkAction::make('unapprove')
                        ->action(fn ($records) => $records->each->update(['approved' => false]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}