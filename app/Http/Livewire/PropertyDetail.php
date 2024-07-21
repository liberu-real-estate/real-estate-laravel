<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyDetail extends Component
{
    public $property;

    public function mount($propertyId)
    {
        $this->property = Property::findOrFail($propertyId);
    }

    public function initiateBooking()
    {
        return redirect()->route('property.booking', ['propertyId' => $this->property->id]);
    }

    public function render()
    {
        return view('livewire.property-detail')->layout('layouts.app');
    }
}