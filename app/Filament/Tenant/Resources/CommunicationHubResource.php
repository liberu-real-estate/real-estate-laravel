<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\CommunicationHubResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CommunicationHubResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static ?string $navigationLabel = 'Communication Hub';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('recipient_id')
                    ->relationship('recipient', 'name')
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')->label('From'),
                Tables\Columns\TextColumn::make('recipient.name')->label('To'),
                Tables\Columns\TextColumn::make('content')->limit(50),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCommunicationHub::route('/'),
            'create' => Pages\CreateCommunicationHub::route('/create'),
            'view' => Pages\ViewCommunicationHub::route('/{record}'),
        ];
    }
}