<?php

namespace App\Filament\Staff\Resources\LeaseAgreements\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\LeaseAgreements\LeaseAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaseAgreements extends ListRecords
{
    protected static string $resource = LeaseAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}