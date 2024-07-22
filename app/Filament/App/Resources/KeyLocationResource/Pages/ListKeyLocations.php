<?php

namespace App\Filament\App\Resources\KeyLocationResource\Pages;

use App\Filament\App\Resources\KeyLocationResource;
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