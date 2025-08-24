<?php

namespace App\Filament\Staff\Resources\Tenants\Pages;

use Filament\Actions\EditAction;
use App\Filament\Staff\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}