<?php

namespace App\Filament\Resources\FavoriteResource\Pages;

use App\Filament\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
