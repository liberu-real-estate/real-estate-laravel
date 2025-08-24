<?php

namespace App\Filament\Staff\Resources\Leads\Pages;

use App\Filament\Staff\Resources\Leads\LeadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;
}