<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Cache;

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
        foreach ($filters as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function dehydrate()
    {
        Cache::forget($this->getCacheKey());
    }

    use Illuminate\Support\Facades\Cache;

    public function getPropertiesProperty()
    {
        try {
            $cacheKey = $this->getCacheKey();
            return Cache::remember($cacheKey, now()->addMinutes(15), function () {
                $query = Property::query()
                    ->search($this->search)
                    ->priceRange($this->minPrice, $this->maxPrice)
                    ->bedrooms($this->minBedrooms, $this->maxBedrooms)
                    ->bathrooms($this->minBathrooms, $this->maxBathrooms)
                    ->areaRange($this->minArea, $this->maxArea);

                if ($this->propertyType) {
                    $query->propertyType($this->propertyType);
                }

                if ($this->selectedAmenities) {
                    $query->hasAmenities($this->selectedAmenities);
                }

                return $query->paginate(12);
            });
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

    private function getCacheKey()
    {
        $params = [
            $this->search,
            $this->minPrice,
            $this->maxPrice,
            $this->minBedrooms,
            $this->maxBedrooms,
            $this->minBathrooms,
            $this->maxBathrooms,
            $this->minArea,
            $this->maxArea,
            $this->propertyType,
            implode(',', $this->selectedAmenities),
            $this->page
        ];

        return 'property_list_' . md5(implode('|', $params));
    }
    
    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}
