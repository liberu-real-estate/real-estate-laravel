<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SavedSearch;

class AlertPreferences extends Component
{
    public $user;
    public $alertFrequency;
    public $propertyTypes = [];
    public $minPrice;
    public $maxPrice;
    public $location;
    public $alertTypes = [];
    public $priceChangeThreshold;

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadPreferences();
    }

    public function loadPreferences()
    {
        $savedSearch = $this->user->savedSearches()->latest()->first();
        if ($savedSearch) {
            $criteria = $savedSearch->criteria;
            $this->alertFrequency = $criteria['frequency'] ?? 'daily';
            $this->propertyTypes = $criteria['property_types'] ?? [];
            $this->minPrice = $criteria['min_price'] ?? null;
            $this->maxPrice = $criteria['max_price'] ?? null;
            $this->location = $criteria['location'] ?? '';
            $this->alertTypes = $criteria['alert_types'] ?? [];
            $this->priceChangeThreshold = $criteria['price_change_threshold'] ?? 5;
        }
    }

    public function savePreferences()
    {
        $this->user->savedSearches()->create([
            'criteria' => [
                'frequency' => $this->alertFrequency,
                'property_types' => $this->propertyTypes,
                'min_price' => $this->minPrice,
                'max_price' => $this->maxPrice,
                'location' => $this->location,
                'alert_types' => $this->alertTypes,
                'price_change_threshold' => $this->priceChangeThreshold,
            ],
        ]);

        session()->flash('message', 'Alert preferences saved successfully.');
    }

    public function render()
    {
        return view('livewire.alert-preferences');
    }
}