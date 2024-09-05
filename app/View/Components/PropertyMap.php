<?php

namespace App\View\Components;

use App\Models\Property;
use Illuminate\View\Component;

class PropertyMap extends Component
{
    public $properties;

    public function __construct($properties = [])
    {
        $this->properties = Property::select('id', 'latitude', 'longitude', 'title', 'location')
            ->where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->get()->toArray();
    }

    public function render()
    {
        return view('components.property-map',[
            'properties' => $this->properties
        ]);
    }
}