<?php

namespace App\Filament\Contractor\Resources\Contractors;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Contractor\Resources\Contractors\Pages\ListContractors;
use App\Filament\Contractor\Resources\Contractors\Pages\CreateContractor;
use App\Filament\Contractor\Resources\Contractors\Pages\EditContractor;
use App\Filament\Contractor\Resources\ContractorResource\Pages;
use App\Filament\Contractor\Resources\ContractorResource\RelationManagers;
use App\Models\Contractor;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class ContractorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'Contractor';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('address')
                    ->maxLength(1000),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('phone'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContractors::route('/'),
            'create' => CreateContractor::route('/create'),
            'edit' => EditContractor::route('/{record}/edit'),
        ];
    }
}
