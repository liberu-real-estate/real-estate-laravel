<?php

namespace App\Filament\Staff\Resources\LandlordResource\Pages;

use App\Filament\Staff\Resources\LandlordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandlord extends EditRecord
{
    protected static string $resource = LandlordResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}