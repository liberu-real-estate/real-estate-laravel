<?php

namespace App\Filament\Staff\Resources\Contractors;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Contractors\Pages\ListContractors;
use App\Filament\Staff\Resources\Contractors\Pages\CreateContractor;
use App\Filament\Staff\Resources\Contractors\Pages\EditContractor;
use App\Models\User;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Filament\Staff\Resources\ContractorResource\Pages;

class ContractorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $modelLabel = 'Contractor';

public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Name'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Password'),
                TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number'),
                Textarea::make('address')
                    ->rows(5)
                    ->label('Address'),
                Select::make('role')
                    ->options([
                        'contractor' => 'Contractor',
                        'landlord' => 'Landlord',
                    ])
                    ->default('contractor')
                    ->required()
                    ->label('Role'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('address')->limit(50),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('contractor');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'User Management';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(['admin', 'staff']);
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
