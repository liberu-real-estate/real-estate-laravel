<?php

namespace App\Filament\App\Resources\FavoriteResource\Pages;

use App\Filament\App\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
