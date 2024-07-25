<?php

namespace App\Filament\Staff\Resources\DocumentResource\Pages;

use App\Filament\Staff\Resources\DocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}