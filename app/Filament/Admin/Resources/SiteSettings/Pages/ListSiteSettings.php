<?php

namespace App\Filament\Admin\Resources\SiteSettings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\SiteSettings\SiteSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}