<?php

namespace App\Filament\Staff\Resources\TenantResource\Pages;

use App\Filament\Staff\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function afterCreate(): void
    {
        $tenantRole = Role::findOrCreate('tenant');
        $this->record->assignRole($tenantRole);
    }
}
