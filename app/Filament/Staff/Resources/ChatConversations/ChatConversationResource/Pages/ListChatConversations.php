<?php

namespace App\Filament\Staff\Resources\ChatConversations\ChatConversationResource\Pages;

use App\Filament\Staff\Resources\ChatConversations\ChatConversationResource;
use Filament\Resources\Pages\ListRecords;

class ListChatConversations extends ListRecords
{
    protected static string $resource = ChatConversationResource::class;
}
