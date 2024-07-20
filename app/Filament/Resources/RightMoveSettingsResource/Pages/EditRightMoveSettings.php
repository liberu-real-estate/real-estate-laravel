<?php

namespace App\Filament\Resources\RightMoveSettingsResource\Pages;

use App\Filament\Resources\RightMoveSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRightMoveSettings extends EditRecord
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}