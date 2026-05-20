<?php

namespace App\Filament\Staff\Resources\Leases\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Leases\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLease extends EditRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}