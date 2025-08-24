<?php

namespace App\Filament\Staff\Resources\BuyerResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\BuyerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuyers extends ListRecords
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}