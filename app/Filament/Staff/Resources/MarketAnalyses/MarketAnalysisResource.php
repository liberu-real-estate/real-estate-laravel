<?php

namespace App\Filament\Staff\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\MarketAnalysisResource\Pages\ListMarketAnalyses;
use App\Filament\Staff\Resources\MarketAnalysisResource\Pages\CreateMarketAnalysis;
use App\Filament\Staff\Resources\MarketAnalysisResource\Pages\ViewMarketAnalysis;
use App\Filament\Staff\Resources\MarketAnalysisResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MarketAnalysisResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Market Analysis';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required()
                    ->after('start_date'),
                Select::make('properties')
                    ->multiple()
                    ->relationship('properties', 'title')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('property_type'),
                TextColumn::make('price')
                    ->money('usd'),
                TextColumn::make('area_sqft'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ListMarketAnalyses::route('/'),
            'create' => CreateMarketAnalysis::route('/create'),
            'view' => ViewMarketAnalysis::route('/{record}'),
        ];
    }
}