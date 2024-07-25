<?php

namespace App\Filament\Staff\Resources\DocumentResource\Pages;

use App\Filament\Staff\Resources\DocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}