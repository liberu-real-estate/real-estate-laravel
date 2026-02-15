<?php

namespace App\Filament\Staff\Resources\ChatConversations\ChatConversationResource\Pages;

use App\Filament\Staff\Resources\ChatConversations\ChatConversationResource;
use App\Models\ChatMessage;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewChatConversation extends ViewRecord
{
    protected static string $resource = ChatConversationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Conversation Info')
                    ->schema([
                        Components\TextEntry::make('session_id')
                            ->label('Session ID'),
                        Components\TextEntry::make('user.name')
                            ->label('User'),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'escalated' => 'warning',
                                'closed' => 'gray',
                            }),
                        Components\TextEntry::make('assignedAgent.name')
                            ->label('Assigned Agent')
                            ->default('Not assigned'),
                        Components\TextEntry::make('created_at')
                            ->label('Started At')
                            ->dateTime(),
                        Components\TextEntry::make('escalated_at')
                            ->label('Escalated At')
                            ->dateTime()
                            ->default('—'),
                    ])
                    ->columns(2),
                Components\Section::make('Messages')
                    ->schema([
                        Components\RepeatableEntry::make('messages')
                            ->schema([
                                Components\TextEntry::make('sender_type')
                                    ->label('From')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'user' => 'primary',
                                        'bot' => 'gray',
                                        'agent' => 'success',
                                        default => 'gray',
                                    }),
                                Components\TextEntry::make('message')
                                    ->label('Message')
                                    ->columnSpanFull(),
                                Components\TextEntry::make('created_at')
                                    ->label('Time')
                                    ->dateTime(),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
