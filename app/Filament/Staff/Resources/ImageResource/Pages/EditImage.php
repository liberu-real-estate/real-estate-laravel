<?php

namespace App\Filament\Staff\Resources\ImageResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\ImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
