<?php

namespace App\Filament\Staff\Resources\Landlords\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Landlords\LandlordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandlord extends EditRecord
{
    protected static string $resource = LandlordResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}