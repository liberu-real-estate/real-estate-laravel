<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Services\AIDescriptionService;
use Livewire\Component;
use Livewire\WithFileUploads;

class PropertySubmissionForm extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $location;
    public $price;
    public $bedrooms;
    public $bathrooms;
    public $area_sqft;
    public $year_built;
    public $property_type;
    public $images = [];
    public $aiDescription;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'bedrooms' => 'required|integer|min:0',
        'bathrooms' => 'required|integer|min:0',
        'area_sqft' => 'required|numeric|min:0',
        'year_built' => 'required|integer|min:1800|max:2099',
        'property_type' => 'required|string|max:255',
        'images.*' => 'image|max:1024', // 1MB Max
    ];

    public function generateAIDescription()
    {
        $this->validate([
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area_sqft' => 'required|numeric|min:0',
            'property_type' => 'required|string|max:255',
        ]);

        $aiService = new AIDescriptionService();
        $this->aiDescription = $aiService->generateDescription([
            'location' => $this->location,
            'price' => $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area_sqft' => $this->area_sqft,
            'property_type' => $this->property_type,
        ]);

        $this->description = $this->aiDescription;
    }

    public function submit()
    {
        $this->validate();

        $property = Property::create([
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area_sqft' => $this->area_sqft,
            'year_built' => $this->year_built,
            'property_type' => $this->property_type,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        foreach ($this->images as $image) {
            $property->addMedia($image->getRealPath())
                ->usingName($image->getClientOriginalName())
                ->toMediaCollection('images');
        }

        session()->flash('message', 'Property submitted successfully and is pending approval.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.property-submission-form');
    }
}