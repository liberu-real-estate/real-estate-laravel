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

        return SpatieMenu::new()
            ->addClass('flex space-x-4')
            ->addItemClass('px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-green-600 transition duration-300 ease-in-out')
            ->add($this->createMenuItems($menuItems));
    }

    private function createMenuItems($items)
    {
        return $items->map(function ($item) {
            $menuItem = Link::to($item->url, $item->name);

            if ($item->children->count() > 0) {
                $submenu = SpatieMenu::new()
                    ->addClass('absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1')
                    ->addItemClass('block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100');

                foreach ($this->createMenuItems($item->children) as $childItem) {
                    $submenu->add($childItem);
                }

                $menuItem->submenu($submenu);
            }

            return $menuItem;
        });
    }
}
