<?php

namespace App\Filament\Staff\Resources\LeaseResource\Pages;

use App\Filament\Staff\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLease extends ViewRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}