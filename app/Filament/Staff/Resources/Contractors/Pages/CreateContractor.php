<?php

namespace App\Filament\Staff\Resources\Contractors\Pages;

use App\Filament\Staff\Resources\Contractors\ContractorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateContractor extends CreateRecord
{
    protected static string $resource = ContractorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = static::getModel()::create($data);
        $user->assignRole('contractor');
        return $user;
    }
}
