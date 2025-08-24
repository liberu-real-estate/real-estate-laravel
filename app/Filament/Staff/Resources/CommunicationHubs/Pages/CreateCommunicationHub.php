<?php

namespace App\Filament\Staff\Resources\CommunicationHubResource\Pages;

use App\Filament\Staff\Resources\CommunicationHubResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunicationHub extends CreateRecord
{
    protected static string $resource = CommunicationHubResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sender_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}