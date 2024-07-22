<?php

<<<<<<< HEAD
namespace App\Filament\App\Resources\Contractors\Pages;

use App\Filament\App\Resources\Contractors\ContractorResource;
=======
namespace App\Filament\Staff\Resources\Contractors\Pages;

use App\Filament\Staff\Resources\Contractors\ContractorResource;
>>>>>>> refs/remotes/origin/main
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractor extends EditRecord
{
    protected static string $resource = ContractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}