<?php

namespace App\Filament\Staff\Resources\BuyerResource\Pages;

use App\Filament\Staff\Resources\BuyerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuyers extends ListRecords
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}