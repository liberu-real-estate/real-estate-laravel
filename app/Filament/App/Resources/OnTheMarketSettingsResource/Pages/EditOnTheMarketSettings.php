<?php

namespace App\Filament\App\Resources\OnTheMarketSettingsResource\Pages;

use App\Filament\App\Resources\OnTheMarketSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnTheMarketSettings extends EditRecord
{
    protected static string $resource = OnTheMarketSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}