<?php

namespace App\Filament\Staff\Resources\UtilityPaymentResource\Pages;

use App\Filament\Staff\Resources\UtilityPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUtilityPayment extends EditRecord
{
    protected static string $resource = UtilityPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}