<?php

namespace App\Filament\Staff\Resources\PropertyCategories;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\PropertyCategories\Pages\ListPropertyCategories;
use App\Filament\Staff\Resources\PropertyCategories\Pages\CreatePropertyCategory;
use App\Filament\Staff\Resources\PropertyCategories\Pages\EditPropertyCategory;
use App\Filament\Staff\Resources\PropertyCategoryResource\Pages;
use App\Models\PropertyCategory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PropertyCategoryResource extends Resource
{
    protected static ?string $model = PropertyCategory::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, Set $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(PropertyCategory::class, 'slug', ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label('Properties'),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPropertyCategories::route('/'),
            'create' => CreatePropertyCategory::route('/create'),
            'edit' => EditPropertyCategory::route('/{record}/edit'),
        ];
    }
}