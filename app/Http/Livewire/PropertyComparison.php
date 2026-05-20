<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyComparison extends Component
{
    public $propertyIds = [];
    public $properties = [];
    public $features = ['price', 'location', 'bedrooms', 'bathrooms', 'area_sqft', 'year_built', 'property_type', 'status'];
    public $searchTerm = '';
    public $searchResults = [];

    public function mount($propertyIds = '')
    {
        $this->propertyIds = $propertyIds ? explode(',', $propertyIds) : [];
        $this->loadProperties();
    }

    public function loadProperties()
    {
        $this->properties = Property::whereIn('id', $this->propertyIds)->get();
    }

    public function addProperty($propertyId)
    {
        if (count($this->propertyIds) < 4 && !in_array($propertyId, $this->propertyIds)) {
            $this->propertyIds[] = $propertyId;
            $this->loadProperties();
        }
    }

    public function removeProperty($propertyId)
    {
        $this->propertyIds = array_diff($this->propertyIds, [$propertyId]);
        $this->loadProperties();
    }

    public function searchProperties()
    {
        if (strlen($this->searchTerm) >= 3) {
            $this->searchResults = Property::search($this->searchTerm)
                ->whereNotIn('id', $this->propertyIds)
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function render()
    {
        return view('livewire.property-comparison');
    }
}