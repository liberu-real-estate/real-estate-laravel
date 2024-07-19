<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyComparison extends Component
{
    public $propertyIds = [];
    public $properties = [];
    public $features = ['price', 'location', 'bedrooms', 'bathrooms', 'area_sqft', 'year_built', 'property_type', 'status'];

    public function mount($propertyIds)
    {
        $this->propertyIds = explode(',', $propertyIds);
        $this->loadProperties();
    }

    public function loadProperties()
    {
        $this->properties = Property::whereIn('property_id', $this->propertyIds)->get();
    }

    public function render()
    {
        return view('livewire.property-comparison');
    }
}