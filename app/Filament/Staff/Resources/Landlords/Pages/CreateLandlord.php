<?php

namespace App\Filament\Staff\Resources\Landlords\Pages;

use App\Filament\Staff\Resources\Landlords\LandlordResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLandlord extends CreateRecord
{
    protected static string $resource = LandlordResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        $record->assignRole('landlord');
        return $record;
    }
}