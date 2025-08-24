<?php

namespace App\Filament\Staff\Resources\EnergyConsumptions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\EnergyConsumptions\EnergyConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnergyConsumptions extends ListRecords
{
    protected static string $resource = EnergyConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}