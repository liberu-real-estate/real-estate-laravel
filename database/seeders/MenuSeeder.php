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
            ['name' => 'Contact', 'url' => '/contact', 'order' => 2],
            ['name' => 'About', 'url' => '/about', 'order' => 3],
            ['name' => 'Properties', 'url' => '/properties', 'order' => 4],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}