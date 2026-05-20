<?php

namespace App\Filament\Admin\Resources\Teams\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}