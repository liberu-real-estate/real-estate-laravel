<?php

namespace App\Filament\Tenant\MaintenanceRequestResource\Pages;

use App\Filament\Tenant\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceRequest extends CreateRecord
{
    protected static string $resource = MaintenanceRequestResource::class;
}