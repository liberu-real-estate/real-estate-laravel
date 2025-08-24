<?php

namespace App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages;

use App\Filament\Tenant\Resources\MaintenanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceRequest extends CreateRecord
{
    protected static string $resource = MaintenanceRequestResource::class;
}