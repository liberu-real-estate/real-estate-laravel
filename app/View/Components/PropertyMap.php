<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PropertyMap extends Component
{
    public $properties;

    public function __construct($properties = [])
    {
        $this->properties = $properties;
    }

    public function render()
    {
        return view('components.property-map');
    }
}