<?php

namespace App\Filament\Staff\Resources\Buyers\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Buyers\BuyerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyer extends EditRecord
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}