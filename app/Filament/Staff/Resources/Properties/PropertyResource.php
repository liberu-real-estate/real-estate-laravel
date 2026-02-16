<?php

namespace App\Filament\Staff\Resources\Properties;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use App\Filament\Staff\Resources\Properties\Pages\ListProperties;
use App\Filament\Staff\Resources\Properties\Pages\CreateProperty;
use App\Filament\Staff\Resources\Properties\Pages\EditProperty;
use App\Filament\Staff\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\RelationManagers\ReviewsRelationManager;
use App\Filament\Staff\Resources\RelationManagers\RoomsRelationManager;
use Illuminate\Support\Collection;
use App\Filament\Forms\Components\FloorPlanEditor;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_template_id')
                    ->label('Template')
                    ->relationship('template', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('content')
                            ->required()
                            ->label('Template Content')
                            ->helperText('Use placeholders like {title}, {description}, {price}, etc.')
                            ->rows(10),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action->modalHeading('Create Property Template');
                    }),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('bedrooms')
                    ->required()
                    ->numeric(),
                TextInput::make('bathrooms')
                    ->required()
                    ->numeric(),
                TextInput::make('area_sqft')
                    ->required()
                    ->numeric()
                    ->label('Area (sq ft)'),
                TextInput::make('year_built')
                    ->required()
                    ->numeric()
                    ->minValue(1800)
                    ->maxValue(date('Y'))
                    ->label('Year Built'),
                Select::make('property_type')
                    ->required()
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                        'townhouse' => 'Townhouse',
                    ]),
                Select::make('status')
                    ->required()
                    ->options([
                        'for_sale' => 'For Sale',
                        'for_rent' => 'For Rent',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                    ]),
                DatePicker::make('list_date')
                    ->required(),
                DatePicker::make('sold_date'),
                TextInput::make('virtual_tour_url')
                    ->url()
                    ->maxLength(255)
                    ->label('Virtual Tour URL')
                    ->helperText('Enter the URL for the virtual tour (Matterport, Kuula, etc.)'),
                Select::make('virtual_tour_provider')
                    ->options([
                        'matterport' => 'Matterport',
                        'kuula' => 'Kuula',
                        '3d_vista' => '3D Vista',
                        'seekbeak' => 'Seekbeak',
                        'other' => 'Other',
                    ])
                    ->label('Virtual Tour Provider')
                    ->helperText('Select the provider of your virtual tour'),
                Textarea::make('virtual_tour_embed_code')
                    ->rows(3)
                    ->maxLength(2000)
                    ->label('Virtual Tour Embed Code')
                    ->helperText('Paste custom embed code if auto-generation from URL doesn\'t work'),
                Toggle::make('live_tour_available')
                    ->label('Live Virtual Tours Available')
                    ->helperText('Enable this to allow users to schedule live virtual tours with agents')
                    ->default(false),
                Toggle::make('is_featured')
                    ->required(),
                Select::make('property_category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Property Category'),
                Select::make('energy_rating')
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
                TextInput::make('energy_score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Energy Efficiency Score'),
                DatePicker::make('energy_rating_date')
                    ->label('Energy Rating Date'),
                Repeater::make('features')
                    ->relationship()
                    ->schema([
                        TextInput::make('feature_name')
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
                Textarea::make('custom_description')
                    ->label('Custom Description')
                    ->maxLength(1000),
                SpatieMediaLibraryFileUpload::make('video')
                    ->collection('videos')
                    ->maxFiles(1)
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime'])
                    ->maxSize(102400), // 100MB
                FloorPlanEditor::make('floor_plan_data')
                    ->label('Interactive Floor Plan')
                    ->columnSpanFull()
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
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('bedrooms')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bathrooms')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('property_type')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('custom_description')
                    ->label('Custom Description')
                    ->limit(50),
                IconColumn::make('has_video')
                    ->label('Has Video')
                    ->boolean()
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-x-circle')
                    ->getStateUsing(fn (Property $record): bool => $record->hasMedia('videos')),
            ])
            ->filters([
                SelectFilter::make('property_type'),
                SelectFilter::make('status'),
                Filter::make('is_featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('updateFeatured')
                        ->label('Update Featured Status')
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['is_featured' => $data['is_featured']]);
                            });
                        })
                        ->schema([
                            Toggle::make('is_featured')
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
        'index' => ListProperties::route('/'),
        'create' => CreateProperty::route('/create'),
        'edit' => EditProperty::route('/{record}/edit'),
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
