<?php
namespace App\Menus;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Link;

class MainMenu
{
    public static function render()
    {
        return Menu::new()
            ->add(Link::to(route('home'), 'Home'))
            ->add(Link::to(route('about'), 'About'))
            // ->add(Link::to(route('contact'), 'Contact'))
            ->add(Link::to('/properties', 'Properties'))
            ->add(Link::to('/services', 'Services'))
            ->render();
    }
}