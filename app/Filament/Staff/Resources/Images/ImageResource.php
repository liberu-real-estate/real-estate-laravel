<?php

namespace App\Filament\Staff\Resources\Images;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Images\Pages\ListImages;
use App\Filament\Staff\Resources\Images\Pages\CreateImage;
use App\Filament\Staff\Resources\Images\Pages\EditImage;
use App\Filament\Staff\Resources\Images\Pages\ViewImage;
use App\Filament\Staff\Resources\ImageResource\Pages;
use App\Filament\Staff\Resources\ImageResource\RelationManagers;
use App\Models\Image;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_id')
                ->relationship('property', 'title'),
                FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('property.title')
                    ->label('Property')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
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
            'index' => ListImages::route('/'),
            'create' => CreateImage::route('/create'),
            'edit' => EditImage::route('/{record}/edit'),
            'view' => ViewImage::route('/{record}'),
        ];
    }
}
