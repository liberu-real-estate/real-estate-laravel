<?php

namespace App\Filament\Staff\Resources\PropertyModerations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\PropertyModerations\PropertyModerationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyModerations extends ListRecords
{
    protected static string $resource = PropertyModerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}