<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;

class PropertyList extends Component
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

    protected $listeners = ['applyAdvancedFilters'];

    public function applyAdvancedFilters($filters)
    {
        $this->search = $filters['search'];
        $this->minPrice = $filters['minPrice'];
        $this->maxPrice = $filters['maxPrice'];
        $this->minBedrooms = $filters['minBedrooms'];
        $this->maxBedrooms = $filters['maxBedrooms'];
        $this->minBathrooms = $filters['minBathrooms'];
        $this->maxBathrooms = $filters['maxBathrooms'];
        $this->minArea = $filters['minArea'];
        $this->maxArea = $filters['maxArea'];
        $this->propertyType = $filters['propertyType'];
        $this->selectedAmenities = $filters['selectedAmenities'];

        $this->resetPage();
    }

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
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ]);
    }
}
        // ])->with(['bookingLink' => function($property) {
        //     return route('livewire.property-booking', ['propertyId' => $property->id]);
        // }]);
