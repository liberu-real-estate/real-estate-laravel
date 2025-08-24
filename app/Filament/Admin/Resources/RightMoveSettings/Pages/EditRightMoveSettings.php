<?php

namespace App\Filament\Admin\Resources\RightMoveSettings\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\RightMoveSettings\RightMoveSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRightMoveSettings extends EditRecord
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}