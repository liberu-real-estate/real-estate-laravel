<?php

namespace App\Filament\Staff\Resources\Leases;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Leases\Pages\ListLeases;
use App\Filament\Staff\Resources\Leases\Pages\CreateLease;
use App\Filament\Staff\Resources\Leases\Pages\ViewLease;
use App\Filament\Staff\Resources\Leases\Pages\EditLease;
use App\Filament\Staff\Resources\LeaseResource\Pages;
use App\Models\Lease;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaseResource extends Resource
{
    protected static ?string $model = Lease::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_id')
                    ->relationship('property', 'address')
                    ->required()
                    ->searchable(),
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->searchable(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('rent_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'terminated' => 'Terminated',
                    ])
                    ->required(),
                Textarea::make('terms')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.address')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tenant.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('rent_amount')
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'terminated' => 'Terminated',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('renew')
                    ->action(fn (Lease $record) => $record->renew(
                        $record->end_date->addYear(),
                        $record->rent_amount * 1.03
                    ))
                    ->requiresConfirmation()
                    ->visible(fn (Lease $record) => $record->isUpForRenewal()),
                Action::make('terminate')
                    ->action(fn (Lease $record) => $record->terminate(now()))
                    ->requiresConfirmation()
                    ->visible(fn (Lease $record) => $record->status === 'active'),
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
            'index' => ListLeases::route('/'),
            'create' => CreateLease::route('/create'),
            'view' => ViewLease::route('/{record}'),
            'edit' => EditLease::route('/{record}/edit'),
        ];
    }
}