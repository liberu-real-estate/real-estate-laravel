<?php

namespace App\Filament\Tenant\MaintenanceRequestResource\Pages;

use App\Filament\Tenant\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceRequest extends EditRecord
{
    protected static string $resource = MaintenanceRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}