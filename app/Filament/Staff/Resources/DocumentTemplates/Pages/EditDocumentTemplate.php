<?php

namespace App\Filament\Staff\Resources\DocumentTemplateResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\DocumentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentTemplate extends EditRecord
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}