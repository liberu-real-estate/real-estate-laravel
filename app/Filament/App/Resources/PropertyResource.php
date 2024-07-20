<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use App\Filament\App\Resources\PropertyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PropertyResource\RelationManagers;
use Filament\Forms\Components\FileUpload;
use App\Services\ImageUploadService;

use App\Services\RightMoveService;
use Filament\Notifications\Notification;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Title'),
                TextArea::make('description')->label('Description'),
                TextInput::make('location')->label('Location'),
                TextInput::make('price')->label('Price')
                    ->numeric()
                    ->prefix('GBP'),
                TextInput::make('bedrooms')->label('Bedrooms')
                    ->numeric(),
                TextInput::make('bathrooms')
                    ->numeric(),
                TextInput::make('area_sqft')
                    ->numeric(),
                DatePicker::make('year_built'),
                Select::make('property_type')->options([
                    'house' => 'House',
                    'apartment' => 'Apartment',
                    'condo' => 'Condo',
                ])->label('Property Type'),
                Select::make('status')->options([
                    'listed' => 'Listed',
                    'sold' => 'Sold',
                ])->label('Status'),
                Datepicker::make('list_date'),
                Datepicker::make('sold_date'),
                BelongsToSelect::make('user_id')
                    ->relationship('user', 'name'),
                BelongsToSelect::make('agent_id')
                    ->relationship('agent', 'name'),
                FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->maxSize(5120)
                    ->directory('property-images')
                    ->saveUploadedFileUsing(function ($file) {
                        $imageUploadService = app(ImageUploadService::class);
                        return $imageUploadService->uploadAndProcess($file);
                    }),
                FileUpload::make('virtual_tour_url')
                    ->label('Virtual Tour')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'video/mp4'])
                    ->maxSize(20480) // 20MB max size
                    ->directory('virtual-tours')
                    ->saveUploadedFileUsing(function ($file) {
                        $virtualTourService = app(VirtualTourService::class);
                        return $virtualTourService->uploadAndProcess($file);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('title')
                        ->label('Title')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('description')
                        ->label('Description')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('location')
                        ->label('Location')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('price')
                        ->label('Price')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('bedrooms')
                        ->label('Bedrooms')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('bathrooms')
                        ->label('Bathrooms')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('area_sqft')
                        ->label('Area (sqft)')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('year_built')
                        ->label('Year Built')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('property_type')
                        ->label('Property Type')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('status')
                        ->label('Status')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('list_date')
                        ->label('List Date')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('sold_date')
                        ->label('Sold Date')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('user_id')
                        ->label('User ID')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('agent_id')
                        ->label('Agent ID')
                        ->searchable()
                        ->sortable(),
                TextColumn::make('rightmove_status')
                    ->label('RightMove Status')
                    ->sortable(),
                TextColumn::make('rightmove_last_sync')
                    ->label('Last Synced')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('valuate')
                    ->label('Valuate')
                    ->icon('heroicon-o-calculator')
                    ->url(fn (Property $record): string => route('filament.app.resources.properties.valuate', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('mortgage_calculator')
                    ->label('Mortgage Calculator')
                    ->icon('heroicon-o-currency-pound')
                    ->url(fn (Property $record): string => route('filament.app.resources.mortgage-calculator.index', ['property_price' => $record->price]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('sync_to_rightmove')
                    ->label('Sync to RightMove')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Property $record) {
                        if ($record->syncToRightMove()) {
                            Notification::make()
                                ->title('Property synced to RightMove')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to sync property to RightMove')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('compare')
                        ->label('Compare Selected')
                        ->action(function (Collection $records) {
                            $propertyIds = $records->pluck('property_id')->join(',');
                            return redirect()->route('property.compare', ['propertyIds' => $propertyIds]);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->icon('heroicon-o-scale'),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->url(route('filament.app.resources.properties.import'))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('sync_to_rightmove')
                    ->label('Sync to RightMove')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Collection $records) {
                        $rightMoveService = app(RightMoveService::class);
                        $successCount = 0;
                        $failCount = 0;

                        foreach ($records as $record) {
                            if ($rightMoveService->updateProperty($record)) {
                                $successCount++;
                            } else {
                                $failCount++;
                            }
                        }

                        Notification::make()
                            ->title("Sync to RightMove completed")
                            ->body("{$successCount} properties synced successfully, {$failCount} failed")
                            ->send();
                    }),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
