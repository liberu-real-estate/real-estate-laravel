<?php

namespace App\Filament\Staff\Resources\PropertyCategories\Pages;

use App\Filament\Staff\Resources\PropertyCategories\PropertyCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePropertyCategory extends CreateRecord
{
    protected static string $resource = PropertyCategoryResource::class;
}