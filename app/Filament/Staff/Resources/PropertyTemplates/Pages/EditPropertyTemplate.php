<?php

namespace App\Filament\Staff\Resources\PropertyTemplates\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\PropertyTemplates\PropertyTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyTemplate extends EditRecord
{
    protected static string $resource = PropertyTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}