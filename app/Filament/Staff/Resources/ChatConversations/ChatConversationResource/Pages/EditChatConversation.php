<?php

namespace App\Filament\Staff\Resources\ChatConversations\ChatConversationResource\Pages;

use App\Filament\Staff\Resources\ChatConversations\ChatConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChatConversation extends EditRecord
{
    protected static string $resource = ChatConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
