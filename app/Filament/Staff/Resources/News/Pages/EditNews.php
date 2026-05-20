<?php

namespace App\Filament\Staff\Resources\News\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\News\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
