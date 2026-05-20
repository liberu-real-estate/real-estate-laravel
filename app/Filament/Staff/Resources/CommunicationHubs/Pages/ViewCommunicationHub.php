<?php

namespace App\Filament\Staff\Resources\CommunicationHubs\Pages;

use Filament\Actions\EditAction;
use App\Filament\Staff\Resources\CommunicationHubs\CommunicationHubResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCommunicationHub extends ViewRecord
{
    protected static string $resource = CommunicationHubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}