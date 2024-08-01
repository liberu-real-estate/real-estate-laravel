<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\AgentPerformanceResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgentPerformanceResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Agent Performance';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('agent');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('listings_count')
                    ->label('Listings')
                    ->counts('properties')
                    ->sortable(),
                Tables\Columns\TextColumn::make('properties_sold_count')
                    ->label('Properties Sold')
                    ->counts('properties', function (Builder $query) {
                        $query->where('status', 'sold');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointments_count')
                    ->label('Appointments')
                    ->counts('appointments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Avg. Rating')
                    ->avg('reviews', 'rating')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
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
            'index' => Pages\ListAgentPerformance::route('/'),
            'view' => Pages\ViewAgentPerformance::route('/{record}'),
        ];
    }
}