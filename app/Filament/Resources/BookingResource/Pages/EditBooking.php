<?php

namespace App\Filament\Resources\BookingResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\BookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}