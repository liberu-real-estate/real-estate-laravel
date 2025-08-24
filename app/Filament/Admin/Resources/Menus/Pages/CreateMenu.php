<?php

namespace App\Filament\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Menus\MenuResource;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}