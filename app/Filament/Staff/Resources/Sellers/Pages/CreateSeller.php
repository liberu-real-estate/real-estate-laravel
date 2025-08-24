<?php

namespace App\Filament\Staff\Resources\SellerResource\Pages;

use App\Filament\Staff\Resources\SellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSeller extends CreateRecord
{
    protected static string $resource = SellerResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignRole('seller');
    }
}