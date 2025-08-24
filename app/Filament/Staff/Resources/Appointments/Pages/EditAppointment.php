<?php

namespace App\Filament\Staff\Resources\Appointments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
