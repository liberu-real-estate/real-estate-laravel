<?php

namespace App\Filament\Staff\Resources\Favorites\Pages;

use App\Filament\Staff\Resources\Favorites\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
