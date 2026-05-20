<?php

namespace App\Filament\Staff\Resources\News\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\News\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
