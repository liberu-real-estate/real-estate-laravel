<?php

namespace App\Filament\Staff\Resources\PropertyFeatures\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\PropertyFeatures\PropertyFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyFeatures extends ListRecords
{
    protected static string $resource = PropertyFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
