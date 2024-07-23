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
    public $selectedCategory = '';

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
        try {
            $query = Property::query();

            \Log::info('Initial query count: ' . $query->count());

            $query->search($this->search)
                  ->priceRange($this->minPrice, $this->maxPrice)
                  ->bedrooms($this->minBedrooms, $this->maxBedrooms)
                  ->bathrooms($this->minBathrooms, $this->maxBathrooms)
                  ->areaRange($this->minArea, $this->maxArea);

            \Log::info('After basic filters count: ' . $query->count());

            if ($this->propertyType) {
                $query->propertyType($this->propertyType);
                \Log::info('After property type filter count: ' . $query->count());
            }

            if ($this->selectedAmenities) {
                $query->hasAmenities($this->selectedAmenities);
                \Log::info('After amenities filter count: ' . $query->count());
            }

            // Temporarily comment out the join to isolate any potential issues
            // $query->leftJoin('images', 'properties.id', '=', 'images.property_id')
            //       ->select('properties.*')
            //       ->distinct();

            $query->with('features', 'images');

            $properties = $query->paginate(12);

            \Log::info('Final properties count: ' . $properties->total());
            \Log::info('Current page: ' . $properties->currentPage());
            \Log::info('Total pages: ' . $properties->lastPage());
            \Log::info('Items per page: ' . $properties->perPage());
            \Log::info('Properties on this page: ' . $properties->count());

            if ($this->selectedCategory) {
                $query->where('property_category_id', $this->selectedCategory);
            }

            $query->with('features', 'images', 'category');

            $properties = $query->paginate(12);

            return $properties;
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
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
            'categories' => PropertyCategory::all(),
        ])->layout('layouts.app');
    }
}
