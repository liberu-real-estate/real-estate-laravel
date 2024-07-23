<?php

namespace App\Filament\Staff\Resources\LeaseResource\Pages;

use App\Filament\Staff\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLease extends EditRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}