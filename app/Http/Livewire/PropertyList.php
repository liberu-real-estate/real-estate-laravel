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

    public function getPropertiesProperty()
    {
        $cacheKey = $this->getCacheKey();

        $properties = Cache::remember($cacheKey, now()->addMinutes(15), function () {
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
                session()->flash('error', 'An error occurred while fetching properties. Please try again.');
                if (app()->environment('local')) {
                    session()->flash('error_details', $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
                }
                return Property::paginate(0);
            }
        });

        $this->dispatch('propertiesUpdated', $properties->items());
        return $properties;
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->emit('filtersChanged', $this->getFilters());
    }

    public function applyFilters($filters)
    {
        $this->fill($filters);
        $this->resetPage();
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
        ];
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
    
    public function viewProperty($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'property_view',
            'description' => "Viewed property: {$property->title}",
            'property_id' => $propertyId,
        ]);
    
        $this->emit('updateRecommendations');
    }
}
