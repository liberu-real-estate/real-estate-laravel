<?php

namespace App\Filament\Resources;

use App\Models\Buyer;
use App\Models\Lead;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Tabs;

class BuyerResource extends Resource
{
    protected static ?string $model = Buyer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

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
                Forms\Components\Select::make('status')
                    ->options([
                        'lead' => 'Lead',
                        'prospect' => 'Prospect',
                        'client' => 'Client',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'lead' => 'Lead',
                        'prospect' => 'Prospect',
                        'client' => 'Client',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Buyer Information')
                    ->tabs([
                        Tabs\Tab::make('Details')
                            ->schema([
                                Infolists\Components\TextEntry::make('name'),
                                Infolists\Components\TextEntry::make('email'),
                                Infolists\Components\TextEntry::make('status'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->dateTime(),
                            ]),
                        Tabs\Tab::make('Activities')
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('activities')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('type'),
                                        Infolists\Components\TextEntry::make('description'),
                                        Infolists\Components\TextEntry::make('scheduled_at')
                                            ->dateTime(),
                                        Infolists\Components\TextEntry::make('completed_at')
                                            ->dateTime(),
                                    ])
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'User Management';
    }
}
