<?php

namespace App\Filament\Resources\AlertResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AlertResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlert extends EditRecord
{
    protected static string $resource = AlertResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}