<?php

namespace App\Filament\Tenant\Resources\MaintenanceRequests\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Tenant\Resources\MaintenanceRequests\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceRequests extends ListRecords
{
    protected static string $resource = MaintenanceRequestResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}