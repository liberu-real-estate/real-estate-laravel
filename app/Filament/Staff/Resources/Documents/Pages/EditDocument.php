<?php

namespace App\Filament\Staff\Resources\Documents\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Documents\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}