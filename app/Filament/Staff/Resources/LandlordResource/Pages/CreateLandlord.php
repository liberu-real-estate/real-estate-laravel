<?php

namespace App\Filament\Staff\Resources\LandlordResource\Pages;

use App\Filament\Staff\Resources\LandlordResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;

class CreateLandlord extends CreateRecord
{
    protected static string $resource = LandlordResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $user = static::getModel()::create($data);
        $user->assignRole('landlord');
        return $user;
    }
}