<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\RentalApplicationResource\Pages;
use App\Models\RentalApplication;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RentalApplicationResource extends Resource
{
    protected static ?string $model = RentalApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('employment_status')
                    ->required(),
                Forms\Components\TextInput::make('annual_income')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('background_check_status'),
                Forms\Components\TextInput::make('credit_report_status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title'),
                Tables\Columns\TextColumn::make('tenant.name'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('employment_status'),
                Tables\Columns\TextColumn::make('annual_income'),
                Tables\Columns\TextColumn::make('background_check_status'),
                Tables\Columns\TextColumn::make('credit_report_status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRentalApplications::route('/'),
            'create' => Pages\CreateRentalApplication::route('/create'),
            'edit' => Pages\EditRentalApplication::route('/{record}/edit'),
        ];
    }
}