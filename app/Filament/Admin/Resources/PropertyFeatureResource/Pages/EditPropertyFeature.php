<?php

namespace App\Filament\Admin\Resources\PropertyFeatureResource\Pages;

use App\Filament\Admin\Resources\PropertyFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyFeature extends EditRecord
{
    protected static string $resource = PropertyFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}