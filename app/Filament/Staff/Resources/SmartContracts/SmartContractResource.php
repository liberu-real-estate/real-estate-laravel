<?php

namespace App\Filament\Staff\Resources\SmartContracts;

use App\Models\SmartContract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Filament\Staff\Resources\SmartContracts\Pages;
use Filament\Support\Colors\Color;

class SmartContractResource extends Resource
{
    protected static ?string $model = SmartContract::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?string $navigationLabel = 'Smart Contracts';

    protected static ?string $navigationGroup = 'Contract Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contract Details')
                    ->schema([
                        Forms\Components\Select::make('lease_agreement_id')
                            ->relationship('leaseAgreement', 'id')
                            ->label('Lease Agreement')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'address')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('landlord_id')
                            ->relationship('landlord', 'name')
                            ->label('Landlord')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required()
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Financial Terms')
                    ->schema([
                        Forms\Components\TextInput::make('rent_amount')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->label('Monthly Rent'),
                        Forms\Components\TextInput::make('security_deposit')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\DateTimePicker::make('lease_start_date')
                            ->required(),
                        Forms\Components\DateTimePicker::make('lease_end_date')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Blockchain Details')
                    ->schema([
                        Forms\Components\TextInput::make('contract_address')
                            ->label('Contract Address')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('blockchain_network')
                            ->options([
                                'simulated' => 'Simulated (Development)',
                                'ethereum' => 'Ethereum Mainnet',
                                'polygon' => 'Polygon',
                                'sepolia' => 'Sepolia Testnet',
                            ])
                            ->default('simulated'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'terminated' => 'Terminated',
                            ])
                            ->default('pending')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('transaction_hash')
                            ->label('Transaction Hash')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Signature Status')
                    ->schema([
                        Forms\Components\Toggle::make('landlord_signed')
                            ->label('Landlord Signed')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Toggle::make('tenant_signed')
                            ->label('Tenant Signed')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contract_address')
                    ->label('Contract Address')
                    ->limit(20)
                    ->searchable()
                    ->copyable()
                    ->tooltip(fn ($record) => $record->contract_address),
                Tables\Columns\TextColumn::make('property.address')
                    ->label('Property')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rent_amount')
                    ->label('Monthly Rent')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'gray' => 'completed',
                        'danger' => 'terminated',
                    ]),
                Tables\Columns\IconColumn::make('landlord_signed')
                    ->label('Landlord')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\IconColumn::make('tenant_signed')
                    ->label('Tenant')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('blockchain_network')
                    ->label('Network')
                    ->badge(),
                Tables\Columns\TextColumn::make('deployed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'terminated' => 'Terminated',
                    ]),
                Tables\Filters\SelectFilter::make('blockchain_network')
                    ->options([
                        'simulated' => 'Simulated',
                        'ethereum' => 'Ethereum',
                        'polygon' => 'Polygon',
                    ]),
                Tables\Filters\Filter::make('fully_signed')
                    ->query(fn ($query) => $query->where('landlord_signed', true)->where('tenant_signed', true))
                    ->label('Fully Signed'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('deployed_at', 'desc');
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
            'index' => Pages\ListSmartContracts::route('/'),
            'create' => Pages\CreateSmartContract::route('/create'),
            'view' => Pages\ViewSmartContract::route('/{record}'),
            'edit' => Pages\EditSmartContract::route('/{record}/edit'),
        ];
    }
}
