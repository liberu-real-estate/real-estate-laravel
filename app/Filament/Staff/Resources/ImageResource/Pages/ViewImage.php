<?php

namespace App\Filament\Staff\Resources\ImageResource\Pages;

use App\Filament\Staff\Resources\ImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewImage extends ViewRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}