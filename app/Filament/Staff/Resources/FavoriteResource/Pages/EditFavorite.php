<?php

namespace App\Filament\App\Resources\FavoriteResource\Pages;

use App\Filament\App\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFavorite extends EditRecord
{
    protected static string $resource = FavoriteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
