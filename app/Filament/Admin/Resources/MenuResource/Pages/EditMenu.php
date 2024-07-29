<?php

namespace App\Filament\Admin\Resources\MenuResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\MenuResource;
use Filament\Actions;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}