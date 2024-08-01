<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\RelationManagers\ReviewsRelationManager;
use App\Filament\Staff\Resources\RelationManagers\RoomsRelationManager;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_template_id')
                    ->label('Template')
                    ->relationship('template', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->label('Template Content')
                            ->helperText('Use placeholders like {title}, {description}, {price}, etc.')
                            ->rows(10),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action->modalHeading('Create Property Template');
                    }),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('bedrooms')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('bathrooms')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('area_sqft')
                    ->required()
                    ->numeric()
                    ->label('Area (sq ft)'),
                Forms\Components\TextInput::make('year_built')
                    ->required()
                    ->numeric()
                    ->minValue(1800)
                    ->maxValue(date('Y'))
                    ->label('Year Built'),
                Forms\Components\Select::make('property_type')
                    ->required()
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                        'townhouse' => 'Townhouse',
                    ]),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'for_sale' => 'For Sale',
                        'for_rent' => 'For Rent',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                    ]),
                Forms\Components\DatePicker::make('list_date')
                    ->required(),
                Forms\Components\DatePicker::make('sold_date'),
                Forms\Components\TextInput::make('virtual_tour_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\Select::make('property_category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Property Category'),
                Forms\Components\Select::make('energy_rating')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'E',
                        'F' => 'F',
                        'G' => 'G',
                    ])
                    ->label('Energy Efficiency Rating'),
                Forms\Components\TextInput::make('energy_score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Energy Efficiency Score'),
                Forms\Components\DatePicker::make('energy_rating_date')
                    ->label('Energy Rating Date'),
                Forms\Components\Repeater::make('features')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('feature_name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('property_images')
                    ->multiple()
                    ->maxFiles(5)
                    ->label('Property Images')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->collection('property_images')
                    ->label('Preview')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bedrooms')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bathrooms')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('property_type'),
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\Filter::make('is_featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateFeatured')
                        ->label('Update Featured Status')
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['is_featured' => $data['is_featured']]);
                            });
                        })
                        ->form([
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Featured')
                                ->required(),
                        ]),
                ]),
            ]);
    }

public static function getRelations(): array
{
    return [
        ReviewsRelationManager::class,
        RoomsRelationManager::class,
    ];
}
public static function getPages(): array
{
    return [
        'index' => Pages\ListProperties::route('/'),
        'create' => Pages\CreateProperty::route('/create'),
        'edit' => Pages\EditProperty::route('/{record}/edit'),
    ];
}
public static function canViewRelation(string $relationName, $record): bool
{
    if ($relationName === 'rooms') {
        return $record->isHmo();
    }
    return true;
}
}
