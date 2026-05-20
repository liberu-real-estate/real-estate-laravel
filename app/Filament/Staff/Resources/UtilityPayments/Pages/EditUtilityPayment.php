<?php

namespace App\Filament\Staff\Resources\UtilityPayments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\UtilityPayments\UtilityPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUtilityPayment extends EditRecord
{
    protected static string $resource = UtilityPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}