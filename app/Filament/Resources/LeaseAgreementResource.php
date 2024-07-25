<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaseAgreementResource\Pages;
use App\Models\LeaseAgreement;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class LeaseAgreementResource extends Resource
{
    protected static ?string $model = LeaseAgreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'address')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('monthly_rent')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('terms')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name'),
                Tables\Columns\TextColumn::make('property.address'),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\TextColumn::make('end_date'),
                Tables\Columns\TextColumn::make('monthly_rent'),
                Tables\Columns\BooleanColumn::make('is_signed'),
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
            'index' => Pages\ListLeaseAgreements::route('/'),
            'create' => Pages\CreateLeaseAgreement::route('/create'),
            'edit' => Pages\EditLeaseAgreement::route('/{record}/edit'),
        ];
    }
}