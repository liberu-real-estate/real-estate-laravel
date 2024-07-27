<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Menu\Laravel\Menu as SpatieMenu;

class MenuService
{
    public function buildMenu()
    {
        $menuItems = Menu::whereNull('parent_id')->orderBy('order')->get();

        return SpatieMenu::new()->add($this->createMenuItems($menuItems));
    }

    private function createMenuItems($items)
    {
        return $items->map(function ($item) {
            $menuItem = SpatieMenu::new()->link($item->url, $item->name);

            if ($item->children->count() > 0) {
                $menuItem->submenu($this->createMenuItems($item->children));
            }

            return $menuItem;
        });
    }
}
