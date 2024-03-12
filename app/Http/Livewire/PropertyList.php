<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyList extends Component
{
    public $properties;
    public $search = '';

    /**
     * Mount the component and initialize properties with all Property records.
     */
    /**
     * Update the properties list based on the search query.
     * Filters properties by title, location, or description matching the search term.
     */
    public function updatedSearch()
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
