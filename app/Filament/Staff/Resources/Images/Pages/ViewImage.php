<?php

namespace App\Filament\Staff\Resources\Images\Pages;

use Filament\Actions\EditAction;
use App\Filament\Staff\Resources\Images\ImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewImage extends ViewRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}