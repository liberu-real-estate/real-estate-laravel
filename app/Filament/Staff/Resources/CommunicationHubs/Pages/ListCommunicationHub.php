<?php

namespace App\Filament\Staff\Resources\CommunicationHubs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\CommunicationHubs\CommunicationHubResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunicationHub extends ListRecords
{
    protected static string $resource = CommunicationHubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}