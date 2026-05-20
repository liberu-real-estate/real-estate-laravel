<?php

namespace App\Filament\Admin\Resources\ZooplaSettings\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\ZooplaSettings\ZooplaSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZooplaSettings extends EditRecord
{
    protected static string $resource = ZooplaSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}