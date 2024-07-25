<?php

namespace App\Filament\Staff\Resources\LeaseAgreementResource\Pages;

use App\Filament\Staff\Resources\LeaseAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaseAgreements extends ListRecords
{
    protected static string $resource = LeaseAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}