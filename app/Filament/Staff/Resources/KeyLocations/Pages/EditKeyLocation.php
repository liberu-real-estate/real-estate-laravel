<?php

namespace App\Filament\Staff\Resources\KeyLocations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\KeyLocations\KeyLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeyLocation extends EditRecord
{
    protected static string $resource = KeyLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
