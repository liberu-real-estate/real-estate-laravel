<?php

namespace App\Filament\Staff\Resources\ContractorResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\ContractorResource;
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
