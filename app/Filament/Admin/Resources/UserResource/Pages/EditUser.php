<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}