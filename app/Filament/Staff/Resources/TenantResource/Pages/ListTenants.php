<?php

namespace App\Filament\Staff\Resources\TenantResource\Pages;

use App\Filament\Staff\Resources\TenantResource;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;
}
