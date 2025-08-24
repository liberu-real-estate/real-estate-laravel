<?php

namespace App\Filament\Staff\Resources\ContractorResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\ContractorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractor extends EditRecord
{
    protected static string $resource = ContractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
