<?php

namespace App\Filament\App\Resources\KeyLocationResource\Pages;

use App\Filament\App\Resources\KeyLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeyLocation extends EditRecord
{
    protected static string $resource = KeyLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}