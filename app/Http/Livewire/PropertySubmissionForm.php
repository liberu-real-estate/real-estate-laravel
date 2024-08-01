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
    public $video;
    public $customDescription;
    public $aiDescription;
    public $descriptionTone = 'professional';

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
        'images.*' => 'image|max:5120', // 5MB Max
        'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:102400', // 100MB Max
        'customDescription' => 'nullable|string|max:1000',
        'descriptionTone' => 'required|in:professional,casual,luxury',
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
            'descriptionTone' => 'required|in:professional,casual,luxury',
        ]);

        $aiService = new AIDescriptionService();
        $this->aiDescription = $aiService->generateDescription([
            'location' => $this->location,
            'price' => $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area_sqft' => $this->area_sqft,
            'property_type' => $this->property_type,
        ], $this->descriptionTone);

        $this->description = $this->aiDescription;
    }

    public function updateDescription($newDescription)
    {
        $this->description = $newDescription;
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
            'custom_description' => $this->customDescription,
        ]);
    
        foreach ($this->images as $image) {
            $property->addMedia($image->getRealPath())
                ->usingName($image->getClientOriginalName())
                ->toMediaCollection('images');
        }
    
        if ($this->video) {
            $property->addMedia($this->video->getRealPath())
                ->usingName($this->video->getClientOriginalName())
                ->toMediaCollection('videos');
        }
    
        session()->flash('message', 'Property submitted successfully and is pending approval.');
        $this->reset();
    }

    public function preview()
    {
        $this->validate();
        $this->emit('previewProperty', [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area_sqft' => $this->area_sqft,
            'year_built' => $this->year_built,
            'property_type' => $this->property_type,
            'custom_description' => $this->customDescription,
        ]);
    }
    
    public function render()
    {
        return view('livewire.property-submission-form');
    }
}