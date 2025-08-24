<?php

namespace App\Filament\Staff\Resources\RentalApplications\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\RentalApplications\RentalApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalApplications extends ListRecords
{
    protected static string $resource = RentalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}