<?php

namespace App\Filament\Tenant\Resources\Leases\Pages;

use App\Filament\Tenant\Resources\Leases\LeaseResource;
use Filament\Resources\Pages\ListRecords;

class ListLeases extends ListRecords
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for tenants viewing their leases
        ];
    }
}