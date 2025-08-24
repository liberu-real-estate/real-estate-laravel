<?php

namespace App\Filament\Staff\Resources\RentalApplications\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\RentalApplications\RentalApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalApplication extends EditRecord
{
    protected static string $resource = RentalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}