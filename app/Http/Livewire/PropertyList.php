<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyList extends Component
{
    public $properties;
    public $search = '';

    public function mount()
    {
        $this->properties = Property::all();
    }

    public function updatedSearch()
    {
        $this->properties = Property::where('title', 'like', '%' . $this->search . '%')
                                    ->orWhere('location', 'like', '%' . $this->search . '%')
                                    ->orWhere('description', 'like', '%' . $this->search . '%')
                                    ->get();
    }

    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->properties,
        ]);
    }
}
        ])->with(['bookingLink' => function($property) {
            return route('livewire.property-booking', ['propertyId' => $property->id]);
        }]);
