<?php

namespace App\Filament\Admin\Resources\Branches;

use Filament\Schemas\Schema;
use App\Filament\Admin\Resources\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Branches\Pages\ListBranches;
use App\Filament\Admin\Resources\Branches\Pages\CreateBranch;
use App\Filament\Admin\Resources\Branches\Pages\EditBranch;
use App\Models\Branch;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Admin\Resources\BranchResource\Pages;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Name'),
                TextInput::make('address')
                    ->required()
                    ->label('Address'),
                TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->label('Phone Number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Address'),
                TextColumn::make('phone_number')
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
        return 'heroicon-o-building-office';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}
