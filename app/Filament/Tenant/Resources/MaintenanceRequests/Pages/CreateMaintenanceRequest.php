<?php

namespace App\Filament\Tenant\Resources\MaintenanceRequests\Pages;

use App\Filament\Tenant\Resources\MaintenanceRequests\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceRequest extends CreateRecord
{
    protected static string $resource = MaintenanceRequestResource::class;
}