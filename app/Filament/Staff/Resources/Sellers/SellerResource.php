<?php

namespace App\Filament\Staff\Resources\Sellers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\Sellers\Pages\ListSellers;
use App\Filament\Staff\Resources\Sellers\Pages\CreateSeller;
use App\Filament\Staff\Resources\Sellers\Pages\EditSeller;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Filament\Staff\Resources\SellerResource\Pages;

class SellerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Seller';

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
                // Add more form fields specific to sellers
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->searchable(),
                // Add more table columns as needed
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
        return parent::getEloquentQuery()->role('seller');
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
            'index' => ListSellers::route('/'),
            'create' => CreateSeller::route('/create'),
            'edit' => EditSeller::route('/{record}/edit'),
        ];
    }
}
