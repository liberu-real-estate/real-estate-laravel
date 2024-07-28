<?php

namespace App\Filament\Staff\Resources\PropertyModerationResource\Pages;

use App\Filament\Staff\Resources\PropertyModerationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyModerations extends ListRecords
{
    protected static string $resource = PropertyModerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}