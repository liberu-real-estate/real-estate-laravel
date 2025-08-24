<?php

namespace App\Filament\Staff\Resources\Landlords;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Landlords\Pages\ListLandlords;
use App\Filament\Staff\Resources\Landlords\Pages\CreateLandlord;
use App\Filament\Staff\Resources\Landlords\Pages\EditLandlord;
use App\Models\User;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Filament\Staff\Resources\LandlordResource\Pages;

class LandlordResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?string $modelLabel = 'Landlord';

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
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number'),
                Textarea::make('address')
                    ->rows(3)
                    ->label('Address'),
                TextInput::make('company_name')
                    ->label('Company Name (if applicable)')
                    ->maxLength(255),
                TextInput::make('tax_id')
                    ->label('Tax ID')
                    ->maxLength(50),
                Select::make('preferred_contact_method')
                    ->options([
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'mail' => 'Mail',
                    ])
                    ->required(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('company_name')->label('Company'),
                TextColumn::make('preferred_contact_method')->label('Preferred Contact'),
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
        return parent::getEloquentQuery()->role('landlord');
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
            'index' => ListLandlords::route('/'),
            'create' => CreateLandlord::route('/create'),
            'edit' => EditLandlord::route('/{record}/edit'),
        ];
    }
}
