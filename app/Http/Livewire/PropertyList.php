<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use App\Services\PropertyFeatureService;

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
    
    public function mount()
    {
        $this->resetPage();
    }
    
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        $cacheKey = $this->getCacheKey();

        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            try {
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

                // Temporarily comment out the join to isolate any potential issues
                // $query->leftJoin('images', 'properties.id', '=', 'images.property_id')
                //       ->select('properties.*')
                //       ->distinct();

                $query->with('features', 'images');

                $properties = $query->paginate(12);

                \Log::info('Properties query executed', [
                    'total' => $properties->total(),
                    'current_page' => $properties->currentPage(),
                    'last_page' => $properties->lastPage(),
                    'per_page' => $properties->perPage(),
                    'count' => $properties->count(),
                ]);

                return $properties;
            } catch (\Exception $e) {
                \Log::error('Error fetching properties', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                session()->flash('error', 'An error occurred while fetching properties. Please try again.');
                if (app()->environment('local')) {
                    session()->flash('error_details', $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
                }
                return collect();
            }
        });
    }

    private function getCacheKey()
    {
        return 'properties_' . md5(json_encode([
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
            $this->selectedAmenities,
        ]));
    }
    
    
    protected $propertyFeatureService;
    
    public function boot(PropertyFeatureService $propertyFeatureService)
    {
        $this->propertyFeatureService = $propertyFeatureService;
    }
    
    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => $this->propertyFeatureService->getFeatures(),
        ])->layout('layouts.app');
    }
}
