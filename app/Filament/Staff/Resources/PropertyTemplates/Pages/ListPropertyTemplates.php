<?php

namespace App\Filament\Staff\Resources\PropertyTemplates\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\PropertyTemplates\PropertyTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyTemplates extends ListRecords
{
    protected static string $resource = PropertyTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}