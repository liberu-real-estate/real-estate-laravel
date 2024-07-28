<?php

namespace App\Filament\Staff\Resources\PropertyModerationResource\Pages;

use App\Filament\Staff\Resources\PropertyModerationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyModerations extends ListRecords
{
    protected static string $resource = PropertyModerationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}