<?php

namespace App\Filament\Staff\Resources\EnergyConsumptionResource\Pages;

use App\Filament\Staff\Resources\EnergyConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnergyConsumptions extends ListRecords
{
    protected static string $resource = EnergyConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}