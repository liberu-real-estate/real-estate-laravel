<?php

namespace App\Filament\Admin\Resources\RightMoveSettingsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\RightMoveSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRightMoveSettings extends ListRecords
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}