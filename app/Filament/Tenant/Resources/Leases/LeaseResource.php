<?php

namespace App\Filament\Tenant\Resources\Leases;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use App\Filament\Tenant\Resources\Leases\Pages\ListLeases;
use App\Filament\Tenant\Resources\Leases\Pages\ViewLease;
use App\Models\Lease;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Tenant\Resources\LeaseResource\Pages;

class LeaseResource extends Resource
{
    protected static ?string $model = Lease::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'My Lease';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('property.address')
                    ->label('Property Address')
                    ->disabled(),
                DatePicker::make('start_date')
                    ->disabled(),
                DatePicker::make('end_date')
                    ->disabled(),
                TextInput::make('rent_amount')
                    ->disabled()
                    ->prefix('$'),
                Select::make('status')
                    ->disabled()
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'terminated' => 'Terminated',
                    ]),
                Textarea::make('terms')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.address')
                    ->label('Property Address'),
                TextColumn::make('start_date')
                    ->date(),
                TextColumn::make('end_date')
                    ->date(),
                TextColumn::make('rent_amount')
                    ->money('usd'),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeases::route('/'),
            'view' => ViewLease::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tenant_id', auth()->id());
    }
}
