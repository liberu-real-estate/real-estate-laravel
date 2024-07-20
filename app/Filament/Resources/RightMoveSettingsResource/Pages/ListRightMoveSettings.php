<?php

namespace App\Filament\Resources\RightMoveSettingsResource\Pages;

use App\Filament\Resources\RightMoveSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRightMoveSettings extends ListRecords
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}