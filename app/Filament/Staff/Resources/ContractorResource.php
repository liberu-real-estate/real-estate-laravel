<?php

namespace App\Filament\Staff\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Filament\Staff\Resources\ContractorResource\Pages;

class ContractorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Contractors';

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Password'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number'),
                Forms\Components\Textarea::make('address')
                    ->rows(5)
                    ->label('Address'),
                Forms\Components\Select::make('role')
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
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('address')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListContractors::route('/'),
            'create' => Pages\CreateContractor::route('/create'),
            'edit' => Pages\EditContractor::route('/{record}/edit'),
        ];
    }
}
