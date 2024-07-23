<?php

namespace App\Filament\Staff\Resources\SellerResource\Pages;

use App\Filament\Staff\Resources\SellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeller extends EditRecord
{
    protected static string $resource = SellerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}