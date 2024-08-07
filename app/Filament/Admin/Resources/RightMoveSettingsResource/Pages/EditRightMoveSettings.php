<?php

namespace App\Filament\Admin\Resources\RightMoveSettingsResource\Pages;

use App\Filament\Admin\Resources\RightMoveSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRightMoveSettings extends EditRecord
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}