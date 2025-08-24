<?php

namespace App\Filament\Staff\Resources\LeadResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\LeadResource;
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