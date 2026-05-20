<?php

namespace App\Filament\Staff\Resources\LeaseAgreements;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\Staff\Resources\LeaseAgreements\Pages\ListLeaseAgreements;
use App\Filament\Staff\Resources\LeaseAgreements\Pages\CreateLeaseAgreement;
use App\Filament\Staff\Resources\LeaseAgreements\Pages\EditLeaseAgreement;
use App\Filament\Staff\Resources\LeaseAgreementResource\Pages;
use App\Models\LeaseAgreement;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class LeaseAgreementResource extends Resource
{
    protected static ?string $model = LeaseAgreement::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('property_id')
                    ->relationship('property', 'address')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric(),
                Textarea::make('terms')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name'),
                TextColumn::make('property.address'),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
                TextColumn::make('monthly_rent'),
                BooleanColumn::make('is_signed'),
            ])
            ->filters([
                //
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
            'index' => ListLeaseAgreements::route('/'),
            'create' => CreateLeaseAgreement::route('/create'),
            'edit' => EditLeaseAgreement::route('/{record}/edit'),
        ];
    }
}
