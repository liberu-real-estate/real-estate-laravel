<?php

namespace App\Filament\Staff\Resources\CommunicationHubResource\Pages;

use App\Filament\Staff\Resources\CommunicationHubResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunicationHub extends ListRecords
{
    protected static string $resource = CommunicationHubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}