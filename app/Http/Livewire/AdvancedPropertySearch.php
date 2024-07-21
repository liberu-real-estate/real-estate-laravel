<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;

class AdvancedPropertySearch extends Component
{
    use WithPagination;

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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        return Property::search($this->search)
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
            ->with(['features', 'images'])
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.advanced-property-search', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}