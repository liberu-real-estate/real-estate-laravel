<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}