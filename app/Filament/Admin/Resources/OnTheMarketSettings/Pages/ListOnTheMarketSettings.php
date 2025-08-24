<?php

namespace App\Filament\Admin\Resources\OnTheMarketSettings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\OnTheMarketSettings\OnTheMarketSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnTheMarketSettings extends ListRecords
{
    protected static string $resource = OnTheMarketSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}