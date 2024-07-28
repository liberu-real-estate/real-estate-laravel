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
    public $maxPrice = 10000000;
    public $minBedrooms = 0;
    public $maxBedrooms = 10;
    public $minBathrooms = 0;
    public $maxBathrooms = 10;
    public $minArea = 0;
    public $maxArea = 10000;
    public $propertyType = '';
    public $selectedAmenities = [];

    protected $listeners = ['applyAdvancedFilters'];
    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 10000000],
        'minBedrooms' => ['except' => 0],
        'maxBedrooms' => ['except' => 10],
        'minBathrooms' => ['except' => 0],
        'maxBathrooms' => ['except' => 10],
        'minArea' => ['except' => 0],
        'maxArea' => ['except' => 10000],
        'propertyType' => ['except' => ''],
        'selectedAmenities' => ['except' => []],
    ];

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingMinBedrooms()
    {
        $this->resetPage();
    }

    public function updatingMaxBedrooms()
    {
        $this->resetPage();
    }

    public function updatingMinBathrooms()
    {
        $this->resetPage();
    }

    public function updatingMaxBathrooms()
    {
        $this->resetPage();
    }

    public function updatingMinArea()
    {
        $this->resetPage();
    }

    public function updatingMaxArea()
    {
        $this->resetPage();
    }

    public function updatingPropertyType()
    {
        $this->resetPage();
    }

    public function updatingSelectedAmenities()
    {
        $this->resetPage();
    }

    public function getProperties()
    {
        try {
            $query = Property::query();

            $query->when($this->search, function ($q) {
                return $q->search($this->search);
            })
            ->when($this->minPrice > 0 || $this->maxPrice < 10000000, function ($q) {
                return $q->priceRange($this->minPrice, $this->maxPrice);
            })
            ->when($this->minBedrooms > 0 || $this->maxBedrooms < 10, function ($q) {
                return $q->bedrooms($this->minBedrooms, $this->maxBedrooms);
            })
            ->when($this->minBathrooms > 0 || $this->maxBathrooms < 10, function ($q) {
                return $q->bathrooms($this->minBathrooms, $this->maxBathrooms);
            })
            ->when($this->minArea > 0 || $this->maxArea < 10000, function ($q) {
                return $q->areaRange($this->minArea, $this->maxArea);
            })
            ->when($this->propertyType, function ($q) {
                return $q->propertyType($this->propertyType);
            })
            ->when(!empty($this->selectedAmenities), function ($q) {
                return $q->hasAmenities($this->selectedAmenities);
            });

            $query->with('features', 'images');

            return $query->paginate(12);
        } catch (\Exception $e) {
            \Log::error('Error fetching properties: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'An error occurred while fetching properties. Please try again.');
            if (app()->environment('local')) {
                session()->flash('error_details', $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            }
            return collect();
        }
    }

    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getProperties(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}
