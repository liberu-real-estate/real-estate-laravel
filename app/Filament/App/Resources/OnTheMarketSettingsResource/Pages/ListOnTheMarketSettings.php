<?php

namespace App\Filament\App\Resources\OnTheMarketSettingsResource\Pages;

use App\Filament\App\Resources\OnTheMarketSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnTheMarketSettings extends ListRecords
{
    protected static string $resource = OnTheMarketSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}