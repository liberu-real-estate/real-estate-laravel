<?php

namespace App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Tenant\Resources\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceRequest extends EditRecord
{
    protected static string $resource = MaintenanceRequestResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}