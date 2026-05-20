<?php

namespace App\Filament\Staff\Resources\Images\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Images\ImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImages extends ListRecords
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
