<?php

namespace App\Filament\Staff\Resources\UtilityPayments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\UtilityPayments\UtilityPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUtilityPayments extends ListRecords
{
    protected static string $resource = UtilityPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}