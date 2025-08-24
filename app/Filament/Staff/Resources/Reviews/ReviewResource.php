<?php

namespace App\Filament\Staff\Resources\Reviews;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Reviews\Pages\ListReviews;
use App\Filament\Staff\Resources\Reviews\Pages\CreateReview;
use App\Filament\Staff\Resources\Reviews\Pages\EditReview;
use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Staff\Resources\ReviewResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Staff\Resources\ReviewResource\RelationManagers;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('property_id')
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
