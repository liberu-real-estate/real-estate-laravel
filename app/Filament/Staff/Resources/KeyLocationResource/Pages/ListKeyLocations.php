<?php

namespace App\Filament\Staff\Resources\KeyLocationResource\Pages;

use App\Filament\Staff\Resources\KeyLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeyLocations extends ListRecords
{
    protected static string $resource = KeyLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
