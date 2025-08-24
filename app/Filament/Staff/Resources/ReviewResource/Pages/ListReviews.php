<?php

namespace App\Filament\Staff\Resources\ReviewResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\ReviewResource;

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
