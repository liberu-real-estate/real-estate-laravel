<?php

namespace App\Filament\Staff\Resources\LeaseAgreementResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\LeaseAgreementResource;
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