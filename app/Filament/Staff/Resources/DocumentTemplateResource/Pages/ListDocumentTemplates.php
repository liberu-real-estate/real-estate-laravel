<?php

namespace App\Filament\Staff\Resources\DocumentTemplateResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\DocumentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentTemplates extends ListRecords
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}