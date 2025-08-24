<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Users\UserResource;
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