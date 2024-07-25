<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\RentalApplicationResource\Pages;
use App\Models\RentalApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalApplicationResource extends Resource
{
    protected static ?string $model = RentalApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Select::make('employment_status')
                    ->options([
                        'employed' => 'Employed',
                        'self_employed' => 'Self-employed',
                        'unemployed' => 'Unemployed',
                        'student' => 'Student',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('annual_income')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('ethereum_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('lease_start_date')
                    ->required(),
                Forms\Components\DatePicker::make('lease_end_date')
                    ->required(),
                Forms\Components\Select::make('background_check_status')
                    ->options([
                        'pending' => 'Pending',
                        'passed' => 'Passed',
                        'failed' => 'Failed',
                    ]),
                Forms\Components\Select::make('credit_report_status')
                    ->options([
                        'pending' => 'Pending',
                        'passed' => 'Passed',
                        'failed' => 'Failed',
                    ]),
                Forms\Components\TextInput::make('smart_contract_address')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('employment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('annual_income')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('background_check_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'passed' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('credit_report_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'poor' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('background_check_status')
                    ->options([
                        'passed' => 'Passed',
                        'failed' => 'Failed',
                        'pending' => 'Pending',
                    ]),
                Tables\Filters\SelectFilter::make('credit_report_status')
                    ->options([
                        'good' => 'Good',
                        'poor' => 'Poor',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->action(fn (RentalApplication $record) => $record->updateStatus('approved'))
                    ->requiresConfirmation()
                    ->visible(fn (RentalApplication $record): bool => $record->status === 'pending' && $record->isScreeningComplete() && $record->isScreeningPassed()),
                Tables\Actions\Action::make('reject')
                    ->action(fn (RentalApplication $record) => $record->updateStatus('rejected'))
                    ->requiresConfirmation()
                    ->visible(fn (RentalApplication $record): bool => $record->status === 'pending'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('employment_status')
                    ->options([
                        'employed' => 'Employed',
                        'self_employed' => 'Self-employed',
                        'unemployed' => 'Unemployed',
                        'student' => 'Student',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

    public static function getNavigationGroup(): ?string
    {
        return 'Property Management';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(['admin', 'staff']);
    }
}
