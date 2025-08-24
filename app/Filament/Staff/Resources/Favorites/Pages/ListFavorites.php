<?php

namespace App\Filament\Staff\Resources\Favorites\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Favorites\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFavorites extends ListRecords
{
    protected static string $resource = FavoriteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
