<?php

namespace App\Filament\Staff\Resources\PropertyModerations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\PropertyModerations\PropertyModerationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyModeration extends EditRecord
{
    protected static string $resource = PropertyModerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}