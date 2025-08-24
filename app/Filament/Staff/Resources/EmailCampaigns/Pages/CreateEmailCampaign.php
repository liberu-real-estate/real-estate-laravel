<?php

namespace App\Filament\Staff\Resources\EmailCampaignResource\Pages;

use App\Filament\Staff\Resources\EmailCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailCampaign extends CreateRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}