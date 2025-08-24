<?php

namespace App\Filament\Staff\Resources\Properties\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Properties\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
