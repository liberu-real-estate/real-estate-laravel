<?php

namespace App\Filament\Staff\Resources\TenantResource\Pages;

use App\Filament\App\Resources\TenantResource;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;
}