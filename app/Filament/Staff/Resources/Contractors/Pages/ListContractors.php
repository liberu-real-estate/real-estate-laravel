<?php

namespace App\Filament\Staff\Resources\Contractors\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Contractors\ContractorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContractors extends ListRecords
{
    protected static string $resource = ContractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
