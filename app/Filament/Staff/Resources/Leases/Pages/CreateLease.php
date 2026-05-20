<?php
namespace App\Filament\Staff\Resources\Leases\Pages;

use App\Filament\Staff\Resources\Leases\LeaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLease extends CreateRecord
{
    protected static string $resource = LeaseResource::class;
}
