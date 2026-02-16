<?php

namespace App\Filament\Staff\Resources\SmartContracts\Pages;

use App\Filament\Staff\Resources\SmartContracts\SmartContractResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmartContract extends EditRecord
{
    protected static string $resource = SmartContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
