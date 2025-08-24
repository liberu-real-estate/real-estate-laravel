<?php

namespace App\Filament\Staff\Resources\SellerResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\SellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellers extends ListRecords
{
    protected static string $resource = SellerResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}