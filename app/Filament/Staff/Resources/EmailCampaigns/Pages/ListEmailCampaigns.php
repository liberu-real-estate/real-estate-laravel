<?php

namespace App\Filament\Staff\Resources\EmailCampaigns\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\EmailCampaigns\EmailCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailCampaigns extends ListRecords
{
    protected static string $resource = EmailCampaignResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}