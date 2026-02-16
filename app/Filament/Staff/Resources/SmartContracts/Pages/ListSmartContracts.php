<?php

namespace App\Filament\Staff\Resources\SmartContracts\Pages;

use App\Filament\Staff\Resources\SmartContracts\SmartContractResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListSmartContracts extends ListRecords
{
    protected static string $resource = SmartContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
