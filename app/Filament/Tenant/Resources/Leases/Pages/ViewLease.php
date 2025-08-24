<?php

namespace App\Filament\Tenant\Resources\LeaseResource\Pages;

use App\Filament\Tenant\Resources\LeaseResource;
use Filament\Resources\Pages\ViewRecord;

class ViewLease extends ViewRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getActions(): array
    {
        return [
            // No actions needed for tenants viewing their lease details
        ];
    }
}