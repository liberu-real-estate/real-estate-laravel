<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyPreviewComponent extends Component
{
    public $property;

    protected $listeners = ['previewProperty'];

    public function previewProperty($propertyData)
    {
        $this->property = new Property($propertyData);
    }

    public function render()
    {
        return view('livewire.property-preview');
    }
}