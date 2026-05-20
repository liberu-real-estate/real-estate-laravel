<?php

namespace App\Filament\Staff\Resources\PropertyFeatures\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\PropertyFeatures\PropertyFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyFeature extends EditRecord
{
    protected static string $resource = PropertyFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
