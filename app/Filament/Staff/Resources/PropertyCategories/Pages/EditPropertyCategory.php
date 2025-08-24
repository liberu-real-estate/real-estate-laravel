<?php

namespace App\Filament\Staff\Resources\PropertyCategoryResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\PropertyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyCategory extends EditRecord
{
    protected static string $resource = PropertyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}