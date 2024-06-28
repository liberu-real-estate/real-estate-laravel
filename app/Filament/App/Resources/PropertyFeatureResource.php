<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PropertyFeature;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertyFeatureResource\Pages;
use App\Filament\Resources\PropertyFeatureResource\RelationManagers;

class PropertyFeatureResource extends Resource
{
    protected static ?string $model = PropertyFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('property_id')
                ->relationship('property', 'title')
                ->required(),
                TextInput::make('feature_name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Property')
                ->searchable()
                ->sortable(),
                TextColumn::make('feature_name')
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
            'index' => Pages\ListPropertyFeatures::route('/'),
            'create' => Pages\CreatePropertyFeature::route('/create'),
            'edit' => Pages\EditPropertyFeature::route('/{record}/edit'),
        ];
    }
}
