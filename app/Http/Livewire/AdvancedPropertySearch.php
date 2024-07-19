<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PropertyFeature;

class AdvancedPropertySearch extends Component
{
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

    public function render()
    {
        $amenities = PropertyFeature::distinct('feature_name')->pluck('feature_name');
        $propertyTypes = ['house', 'apartment', 'condo'];

        return view('livewire.advanced-property-search', [
            'amenities' => $amenities,
            'propertyTypes' => $propertyTypes,
        ]);
    }

    public function search()
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