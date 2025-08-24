<?php

namespace App\Filament\Admin\Resources\Teams\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}