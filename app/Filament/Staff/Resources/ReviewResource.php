<?php

<<<<<<< HEAD
namespace App\Filament\App\Resources;
=======
namespace App\Filament\Staff\Resources;
>>>>>>> refs/remotes/origin/main

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
<<<<<<< HEAD
use App\Filament\App\Resources\ReviewResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ReviewResource\RelationManagers;
=======
use App\Filament\Staff\Resources\ReviewResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Staff\Resources\ReviewResource\RelationManagers;
>>>>>>> refs/remotes/origin/main

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                BelongsToSelect::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                Textarea::make('comment')
                    ->label('Comment')
                    ->required(),
                DatePicker::make('review_date')
                    ->label('Review Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                ->searchable()
                ->sortable(),
            TextColumn::make('property_id')
                ->searchable()
                ->sortable(),
            TextColumn::make('rating')
                ->searchable()
                ->sortable(),
            TextColumn::make('comment')
                ->searchable()
                ->sortable(),
            TextColumn::make('review_date')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> refs/remotes/origin/main
