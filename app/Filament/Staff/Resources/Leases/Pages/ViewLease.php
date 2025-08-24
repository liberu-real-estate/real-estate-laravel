<?php

namespace App\Filament\Staff\Resources\Leases\Pages;

use Filament\Actions\EditAction;
use App\Filament\Staff\Resources\Leases\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLease extends ViewRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}