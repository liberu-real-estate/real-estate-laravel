<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyDetail extends Component
{
    public $property;
    public $neighborhood;
    public $team;

    public function mount($propertyId)
    {
        $this->property = Property::with(['neighborhood', 'features', 'team'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
        $this->team = $this->property->team;
    }

    public function render()
    {
        return view('livewire.property-detail')->layout('layouts.app');
    }
}
