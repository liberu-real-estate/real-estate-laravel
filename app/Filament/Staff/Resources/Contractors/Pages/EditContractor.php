<?php

namespace App\Filament\Staff\Resources\Contractors\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Contractors\ContractorResource;
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
