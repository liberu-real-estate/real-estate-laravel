<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Menu\Laravel\Menu as SpatieMenu;
use Spatie\Menu\Laravel\Link;

class MenuService
{
    public function buildMenu()
    {
        $menuItems = Menu::whereNull('parent_id')->orderBy('order')->get();

        $menu = SpatieMenu::new()
            ->addClass('flex items-center space-x-4')
            ->addItemClass('px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out');

        $this->createMenuItems($menuItems)->forEach(function ($item) use ($menu) {
            $menu->add($item);
        });

        return $menu;
    }

    private function createMenuItems($items)
    {
        return $items->map(function ($item) {
            $menuItem = Link::to($item->url, $item->name);

            if ($item->children->count() > 0) {
                $submenu = SpatieMenu::new()
                    ->addClass('absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1')
                    ->addItemClass('block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100')
                    ->add($this->createMenuItems($item->children));

                $menuItem->submenu($submenu);
            }

            return $menuItem;
        });
    }
}
