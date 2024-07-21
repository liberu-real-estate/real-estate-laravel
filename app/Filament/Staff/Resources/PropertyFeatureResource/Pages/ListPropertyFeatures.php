<?php

namespace App\Filament\Staff\Resources\PropertyFeatureResource\Pages;

use App\Filament\Staff\Resources\PropertyFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyFeatures extends ListRecords
{
    protected static string $resource = PropertyFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
