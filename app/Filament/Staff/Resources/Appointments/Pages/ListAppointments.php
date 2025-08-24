<?php

namespace App\Filament\Staff\Resources\Appointments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
