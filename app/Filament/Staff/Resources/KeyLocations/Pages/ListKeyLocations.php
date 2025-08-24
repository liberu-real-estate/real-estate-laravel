<?php

namespace App\Filament\Staff\Resources\KeyLocations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\KeyLocations\KeyLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeyLocations extends ListRecords
{
    protected static string $resource = KeyLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
