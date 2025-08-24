<?php

namespace App\Filament\Admin\Resources\SiteSettings\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\SiteSettings\SiteSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteSettings extends EditRecord
{
    protected static string $resource = SiteSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}