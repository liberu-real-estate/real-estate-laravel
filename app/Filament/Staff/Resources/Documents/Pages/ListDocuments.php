<?php

namespace App\Filament\Staff\Resources\Documents\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Documents\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}