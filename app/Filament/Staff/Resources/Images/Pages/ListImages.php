<?php

namespace App\Filament\Staff\Resources\ImageResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\ImageResource;
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
