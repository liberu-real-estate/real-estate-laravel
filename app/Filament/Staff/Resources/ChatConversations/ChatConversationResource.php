<?php

namespace App\Filament\Staff\Resources\ChatConversations;

use App\Models\ChatConversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\ChatConversations\ChatConversationResource\Pages;

class ChatConversationResource extends Resource
{
    protected static ?string $model = ChatConversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Chat Conversations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conversation Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('User')
                            ->disabled(),
                        Forms\Components\TextInput::make('session_id')
                            ->label('Session ID')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'escalated' => 'Escalated',
                                'closed' => 'Closed',
                            ])
                            ->required(),
                        Forms\Components\Select::make('assigned_agent_id')
                            ->relationship('assignedAgent', 'name')
                            ->label('Assigned Agent')
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('escalated_at')
                            ->label('Escalated At')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'escalated',
                        'secondary' => 'closed',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedAgent.name')
                    ->label('Assigned Agent')
                    ->searchable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Messages')
                    ->sortable(),
                Tables\Columns\TextColumn::make('escalated_at')
                    ->label('Escalated At')
                    ->dateTime()
                    ->sortable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Started At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'escalated' => 'Escalated',
                        'closed' => 'Closed',
                    ]),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListChatConversations::route('/'),
            'view' => Pages\ViewChatConversation::route('/{record}'),
            'edit' => Pages\EditChatConversation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'escalated')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
