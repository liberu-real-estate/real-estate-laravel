<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;
use App\Services\PostalCodeService;

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
    public $yearBuilt = '';
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $postalCode = '';
    public $latitude = null;
    public $longitude = null;
    public $radius = 10; // Default radius in km
    public $energyRating = '';
    public $minEnergyScore = 0;
    public $minWalkabilityScore = 0;
    public $minTransitScore = 0;
    public $minBikeScore = 0;
    public $featuredOnly = false;
    public $country = '';

    protected $queryString = [
        'search', 'minPrice', 'maxPrice', 'minBedrooms', 'propertyType', 'sortBy',
        'energyRating', 'minEnergyScore', 'minWalkabilityScore', 'minTransitScore',
        'minBikeScore', 'featuredOnly', 'country'
    ];

    protected $postalCodeService;

    public function boot(PostalCodeService $postalCodeService)
    {
        $this->postalCodeService = $postalCodeService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPostalCode()
    {
        $this->validatePostalCode();
    }

    public function validatePostalCode()
    {
        if (!empty($this->postalCode)) {
            $result = $this->postalCodeService->validatePostcode($this->postalCode);
            if (!$result) {
                $this->addError('postalCode', 'Invalid postal code');
            } else {
                $this->latitude = $result['latitude'];
                $this->longitude = $result['longitude'];
            }
        }
    }

    public function getPropertiesProperty()
    {
        $query = Property::search($this->search)
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
            ->when($this->yearBuilt, function ($query) {
                return $query->where('year_built', '>=', $this->yearBuilt);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->postalCode, function ($query) {
                return $query->postalCode($this->postalCode);
            })
            ->when($this->latitude && $this->longitude, function ($query) {
                return $query->nearby($this->latitude, $this->longitude, $this->radius);
            })
            ->when($this->energyRating, function ($query) {
                return $query->energyRating($this->energyRating);
            })
            ->when($this->minEnergyScore > 0, function ($query) {
                return $query->minEnergyScore($this->minEnergyScore);
            })
            ->when($this->minWalkabilityScore > 0, function ($query) {
                return $query->walkabilityScore($this->minWalkabilityScore);
            })
            ->when($this->minTransitScore > 0, function ($query) {
                return $query->transitScore($this->minTransitScore);
            })
            ->when($this->minBikeScore > 0, function ($query) {
                return $query->bikeScore($this->minBikeScore);
            })
            ->when($this->featuredOnly, function ($query) {
                return $query->featured();
            })
            ->when($this->country, function ($query) {
                return $query->country($this->country);
            })
            ->with(['features', 'images']);

        return $this->applySorting($query)->paginate(12);
    }

    protected function applySorting($query)
    {
        switch ($this->sortBy) {
            case 'price_asc':
                return $query->orderBy('price', 'asc');
            case 'price_desc':
                return $query->orderBy('price', 'desc');
            case 'bedrooms':
                return $query->orderBy('bedrooms', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }

    public function search()
    {
        $this->resetPage();
        $this->emit('filtersChanged', $this->getFilters());
    }
    
    private function getFilters()
    {
        return [
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
            'yearBuilt' => $this->yearBuilt,
            'status' => $this->status,
            'postalCode' => $this->postalCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function saveSearch()
    {
        $this->validate([
            'search' => 'required|string|max:255',
        ]);

        auth()->user()->savedSearches()->create([
            'criteria' => [
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
                'yearBuilt' => $this->yearBuilt,
                'status' => $this->status,
                'postalCode' => $this->postalCode,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius' => $this->radius,
            ],
        ]);

        session()->flash('message', 'Search saved successfully!');
    }

    public function render()
    {
        return view('livewire.advanced-property-search', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}