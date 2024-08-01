<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\MarketAnalysisResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MarketAnalysisResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Market Analysis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->after('start_date'),
                Forms\Components\Select::make('properties')
                    ->multiple()
                    ->relationship('properties', 'title')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('property_type'),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd'),
                Tables\Columns\TextColumn::make('area_sqft'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMarketAnalyses::route('/'),
            'create' => Pages\CreateMarketAnalysis::route('/create'),
            'view' => Pages\ViewMarketAnalysis::route('/{record}'),
        ];
    }
}