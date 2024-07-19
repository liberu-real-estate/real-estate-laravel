<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyList extends Component
{
    public $properties;
    public $search = '';
    public $minPrice = 0;
    public $maxPrice = 1000000;
    public $minBedrooms = 0;
    public $maxBedrooms = 10;
    public $minBathrooms = 0;
    public $maxBathrooms = 10;
    public $minArea = 0;
    public $maxArea = 10000;
    public $propertyType = '';
    public $selectedAmenities = [];

    public function mount()
    {
        $this->properties = Property::all();
    }

    public function updatedSearch()
    {
        $this->filterProperties();
    }

    public function filterProperties()
    {
        $this->properties = Property::search($this->search)
            ->priceRange($this->minPrice, $this->maxPrice)
            ->bedrooms($this->minBedrooms, $this->maxBedrooms)
            ->bathrooms($this->minBathrooms, $this->maxBathrooms)
            ->areaRange($this->minArea, $this->maxArea)
            ->when($this->propertyType, function ($query) {
                return $query->propertyType($this->propertyType);
            })
            ->when($this->selectedAmenities, function ($query) {
                return $query->hasAmenities($this->selectedAmenities);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->properties,
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ]);
    }
}
        // ])->with(['bookingLink' => function($property) {
        //     return route('livewire.property-booking', ['propertyId' => $property->id]);
        // }]);
