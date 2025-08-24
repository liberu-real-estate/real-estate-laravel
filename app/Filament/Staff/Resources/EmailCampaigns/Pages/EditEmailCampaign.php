<?php

namespace App\Filament\Staff\Resources\EmailCampaigns\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\EmailCampaigns\EmailCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailCampaign extends EditRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}