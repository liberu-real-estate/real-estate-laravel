<?php

namespace App\Filament\Staff\Resources\Leads\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Leads\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}