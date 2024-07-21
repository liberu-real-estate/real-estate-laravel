<?php

namespace App\Filament\Resources\RightMoveSettingsResource\Pages;

use App\Filament\Resources\RightMoveSettingsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRightMoveSettings extends ListRecords
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}