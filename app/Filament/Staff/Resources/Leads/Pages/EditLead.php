<?php

namespace App\Filament\Staff\Resources\Leads\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Leads\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}