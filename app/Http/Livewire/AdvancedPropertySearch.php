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
    public $drawnArea = null;

    protected $postalCodeService;

    protected $listeners = ['updateDrawnArea'];

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
            }
        }
    }

    public function updateDrawnArea($coordinates)
    {
        $this->drawnArea = $coordinates;
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
            });

        if ($this->drawnArea) {
            $query->whereRaw('ST_Contains(ST_GeomFromText(?), POINT(latitude, longitude))', [$this->drawnArea]);
        }

        return $query->with(['features', 'images'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
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

    public function render()
    {
        $properties = $this->getPropertiesProperty();
        $mapProperties = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'lat' => $property->latitude,
                'lng' => $property->longitude,
                'price' => $property->price,
            ];
        });

        $this->emit('propertiesUpdated', $mapProperties);

        return view('livewire.advanced-property-search', [
            'properties' => $properties,
            'mapProperties' => $mapProperties,
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }

    public function render()
    {
        return view('livewire.advanced-property-search', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}