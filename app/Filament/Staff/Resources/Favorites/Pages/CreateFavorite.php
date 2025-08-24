<?php

namespace App\Filament\Staff\Resources\FavoriteResource\Pages;

use App\Filament\Staff\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
