<?php

namespace App\Filament\Staff\Resources\Tenants\Pages;

use App\Filament\Staff\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;
}
