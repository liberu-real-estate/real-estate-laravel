<?php

namespace App\Filament\Staff\Resources\EmailCampaigns\Pages;

use App\Filament\Staff\Resources\EmailCampaigns\EmailCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailCampaign extends CreateRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}