<?php

namespace App\Filament\Staff\Resources\CommunicationHubResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\CommunicationHubResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunicationHub extends EditRecord
{
    protected static string $resource = CommunicationHubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}