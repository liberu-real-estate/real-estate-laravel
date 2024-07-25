<?php

namespace App\Filament\Staff\Resources\LeaseAgreementResource\Pages;

use App\Filament\Staff\Resources\LeaseAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaseAgreement extends EditRecord
{
    protected static string $resource = LeaseAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}