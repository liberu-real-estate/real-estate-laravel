<?php

namespace App\Filament\Tenant\Resources\Payments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Tenant\Resources\Payments\PaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}