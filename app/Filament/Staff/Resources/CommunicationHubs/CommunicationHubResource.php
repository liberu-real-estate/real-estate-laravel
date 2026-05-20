<?php

namespace App\Filament\Staff\Resources\CommunicationHubs;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\CommunicationHubs\Pages\ListCommunicationHub;
use App\Filament\Staff\Resources\CommunicationHubs\Pages\CreateCommunicationHub;
use App\Filament\Staff\Resources\CommunicationHubs\Pages\ViewCommunicationHub;
use App\Filament\Staff\Resources\CommunicationHubs\Pages\EditCommunicationHub;
use App\Filament\Staff\Resources\CommunicationHubResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class CommunicationHubResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Communication Hub';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('recipient_id')
                    ->relationship('recipient', 'name')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->required()
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.name')->label('From'),
                TextColumn::make('recipient.name')->label('To'),
                TextColumn::make('content')->limit(50),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => ListCommunicationHub::route('/'),
            'create' => CreateCommunicationHub::route('/create'),
            'view' => ViewCommunicationHub::route('/{record}'),
            'edit' => EditCommunicationHub::route('/{record}/edit'),
        ];
    }
}
