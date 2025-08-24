<?php

namespace App\Filament\Staff\Resources\EnergyConsumptionResource\Pages;

use App\Filament\Staff\Resources\EnergyConsumptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnergyConsumption extends CreateRecord
{
    protected static string $resource = EnergyConsumptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}