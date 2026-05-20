<?php

namespace App\Filament\Staff\Resources\EnergyConsumptions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\EnergyConsumptions\EnergyConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnergyConsumption extends EditRecord
{
    protected static string $resource = EnergyConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}