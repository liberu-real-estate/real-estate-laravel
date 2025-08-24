<?php

namespace App\Filament\Staff\Resources\SellerResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\SellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeller extends EditRecord
{
    protected static string $resource = SellerResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}