<?php

namespace App\Filament\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Menus\MenuResource;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;
}