<?php

namespace App\Filament\Admin\Resources\RightMoveSettings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\RightMoveSettings\RightMoveSettingsResource;
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