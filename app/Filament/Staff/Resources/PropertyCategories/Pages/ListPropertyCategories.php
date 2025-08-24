<?php

namespace App\Filament\Staff\Resources\PropertyCategories\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\PropertyCategories\PropertyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyCategories extends ListRecords
{
    protected static string $resource = PropertyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}