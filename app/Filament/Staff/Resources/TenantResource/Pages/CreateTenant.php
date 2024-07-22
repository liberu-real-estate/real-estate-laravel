<?php

namespace App\Filament\Staff\Resources\TenantResource\Pages;

use App\Filament\Staff\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
