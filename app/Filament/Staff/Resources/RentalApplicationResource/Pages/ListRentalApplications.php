<?php

namespace App\Filament\Staff\Resources\RentalApplicationResource\Pages;

use App\Filament\Staff\Resources\RentalApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalApplications extends ListRecords
{
    protected static string $resource = RentalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}