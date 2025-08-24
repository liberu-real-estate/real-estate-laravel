<?php

namespace App\Filament\Admin\Resources\ZooplaSettingsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\ZooplaSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZooplaSettings extends ListRecords
{
    protected static string $resource = ZooplaSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}