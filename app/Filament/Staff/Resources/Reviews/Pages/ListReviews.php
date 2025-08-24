<?php

namespace App\Filament\Staff\Resources\Reviews\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Reviews\ReviewResource;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
