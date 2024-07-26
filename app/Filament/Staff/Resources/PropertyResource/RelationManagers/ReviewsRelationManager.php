<?php

namespace App\Filament\Staff\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->min(1)
                    ->max(5)
                    ->label('Rating (1-5)'),
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->maxLength(1000),
                Forms\Components\DatePicker::make('review_date')
                    ->required(),
                Forms\Components\Toggle::make('approved')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50),
                Tables\Columns\TextColumn::make('review_date')
                    ->date(),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approved')
                    ->options([
                        '1' => 'Approved',
                        '0' => 'Not Approved',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}