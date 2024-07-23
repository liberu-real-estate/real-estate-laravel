<?php

namespace App\Filament\Resources\AlertResource\Pages;

use App\Filament\Resources\AlertResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlerts extends ListRecords
{
    protected static string $resource = AlertResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}