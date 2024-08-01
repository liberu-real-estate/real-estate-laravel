<?php

namespace App\Filament\Staff\Resources\EnergyConsumptionResource\Pages;

use App\Filament\Staff\Resources\EnergyConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnergyConsumption extends EditRecord
{
    protected static string $resource = EnergyConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}