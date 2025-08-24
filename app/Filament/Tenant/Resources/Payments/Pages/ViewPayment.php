<?php

namespace App\Filament\Tenant\Resources\PaymentResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\Tenant\Resources\PaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}