<?php

/**
 * Defines the Filament resource for Branch entities.
 * 
 * This resource class is responsible for defining how Branch entities are represented
 * and managed within the Filament admin panel.
 */

namespace App\Filament\Resources;

use App\Models\Branch;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Address'),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->label('Phone Number'),
            ]);
    }

    protected static function table(Table $table): Table
/**
 * Creates the form schema for Branch entities.
 * 
 * This function defines the fields and their configurations for the form used
 * to create or edit Branch entities.
 * 
 * @param Form $form The form builder instance.
 * @return Form The configured form instance.
 */
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->label('Address'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getLabel(): string
    {
        return 'Branch';
    }

    public static function getPluralLabel(): string
    {
        return 'Branches';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-office-building';
    }
}
/**
 * Defines the table schema for displaying Branch entities.
 * 
 * This function configures the columns and filters for the table that displays
 * Branch entities in the Filament admin panel.
 * 
 * @param Table $table The table builder instance.
 * @return Table The configured table instance.
 */
/**
 * Gets the singular label for the resource.
 * 
 * @return string The singular label.
 */
/**
 * Gets the plural label for the resource.
 * 
 * @return string The plural label.
 */
/**
 * Gets the navigation icon for the resource.
 * 
 * @return string The Heroicons icon name.
 */
