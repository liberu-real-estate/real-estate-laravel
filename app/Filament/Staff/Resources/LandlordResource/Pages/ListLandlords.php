<?php

namespace App\Filament\Staff\Resources\LandlordResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\LandlordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandlords extends ListRecords
{
    protected static string $resource = LandlordResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}