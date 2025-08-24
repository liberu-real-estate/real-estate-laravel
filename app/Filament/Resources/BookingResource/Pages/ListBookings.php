<?php

namespace App\Filament\Resources\BookingResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\BookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}