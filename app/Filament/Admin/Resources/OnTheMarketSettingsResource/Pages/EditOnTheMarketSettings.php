<?php

namespace App\Filament\Admin\Resources\OnTheMarketSettingsResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\OnTheMarketSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnTheMarketSettings extends EditRecord
{
    protected static string $resource = OnTheMarketSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}