<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyDetail extends Component
{
    public $property;
    public $neighborhood;

    public function mount($propertyId)
    {
        $this->property = Property::with(['neighborhood', 'categories', 'features'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
    }

    public function render()
    {
        return view('livewire.property-detail')->layout('layouts.app');
    }
}