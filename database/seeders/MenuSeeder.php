<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            ['name' => 'Home', 'url' => '/', 'order' => 1],
            ['name' => 'Properties', 'url' => '/properties', 'order' => 2],
            [
                'name' => 'Services',
                'url' => '#',
                'order' => 3,
                'children' => [
                    ['name' => 'Buying', 'url' => '/services/buying', 'order' => 1],
                    ['name' => 'Selling', 'url' => '/services/selling', 'order' => 2],
                    ['name' => 'Renting', 'url' => '/services/renting', 'order' => 3],
                ]
            ],
            ['name' => 'About', 'url' => '/about', 'order' => 4],
            ['name' => 'Contact', 'url' => '/contact', 'order' => 5],
        ];

        $this->createMenus($menus);
    }

    private function createMenus($menus, $parentId = null)
    {
        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);
            $menuData['parent_id'] = $parentId;

            $menu = Menu::create($menuData);

            if (!empty($children)) {
                $this->createMenus($children, $menu->id);
            }
        }
    }
}