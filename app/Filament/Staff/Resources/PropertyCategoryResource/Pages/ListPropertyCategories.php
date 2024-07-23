<?php

namespace App\Filament\Staff\Resources\PropertyCategoryResource\Pages;

use App\Filament\Staff\Resources\PropertyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyCategories extends ListRecords
{
    protected static string $resource = PropertyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}