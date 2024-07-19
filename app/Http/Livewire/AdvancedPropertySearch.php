<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PropertyFeature;
use App\Models\Property;

class AdvancedPropertySearch extends Component
{
    public $search = '';
    public $minPrice = 0;
    public $maxPrice;
    public $minBedrooms = 0;
    public $maxBedrooms;
    public $minBathrooms = 0;
    public $maxBathrooms;
    public $minArea = 0;
    public $maxArea;
    public $propertyType = '';
    public $selectedAmenities = [];

    public function mount()
    {
        $this->maxPrice = Property::max('price');
        $this->maxBedrooms = Property::max('bedrooms');
        $this->maxBathrooms = Property::max('bathrooms');
        $this->maxArea = Property::max('area_sqft');
    }

    public function render()
    {
        $amenities = PropertyFeature::distinct('feature_name')->pluck('feature_name');
        $propertyTypes = Property::distinct('property_type')->pluck('property_type');

        return view('livewire.advanced-property-search', [
            'amenities' => $amenities,
            'propertyTypes' => $propertyTypes,
        ]);
    }

    public function updatedSearch()
    {
        $this->emitSearch();
    }

    public function updatedMinPrice()
    {
        $this->validatePrice();
        $this->emitSearch();
    }

    public function updatedMaxPrice()
    {
        $this->validatePrice();
        $this->emitSearch();
    }

    public function updatedMinBedrooms()
    {
        $this->validateBedrooms();
        $this->emitSearch();
    }

    public function updatedMaxBedrooms()
    {
        $this->validateBedrooms();
        $this->emitSearch();
    }

    public function updatedMinBathrooms()
    {
        $this->validateBathrooms();
        $this->emitSearch();
    }

    public function updatedMaxBathrooms()
    {
        $this->validateBathrooms();
        $this->emitSearch();
    }

    public function updatedMinArea()
    {
        $this->validateArea();
        $this->emitSearch();
    }

    public function updatedMaxArea()
    {
        $this->validateArea();
        $this->emitSearch();
    }

    public function updatedPropertyType()
    {
        $this->emitSearch();
    }

    public function updatedSelectedAmenities()
    {
        $this->emitSearch();
    }

    private function validatePrice()
    {
        if ($this->minPrice > $this->maxPrice) {
            $this->minPrice = $this->maxPrice;
        }
    }

    private function validateBedrooms()
    {
        if ($this->minBedrooms > $this->maxBedrooms) {
            $this->minBedrooms = $this->maxBedrooms;
        }
    }

    private function validateBathrooms()
    {
        if ($this->minBathrooms > $this->maxBathrooms) {
            $this->minBathrooms = $this->maxBathrooms;
        }
    }

    private function validateArea()
    {
        if ($this->minArea > $this->maxArea) {
            $this->minArea = $this->maxArea;
        }
    }

    private function emitSearch()
    {
        $this->emitTo('property-list', 'applyAdvancedFilters', [
            'search' => $this->search,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'minBedrooms' => $this->minBedrooms,
            'maxBedrooms' => $this->maxBedrooms,
            'minBathrooms' => $this->minBathrooms,
            'maxBathrooms' => $this->maxBathrooms,
            'minArea' => $this->minArea,
            'maxArea' => $this->maxArea,
            'propertyType' => $this->propertyType,
            'selectedAmenities' => $this->selectedAmenities,
        ]);
    }
}