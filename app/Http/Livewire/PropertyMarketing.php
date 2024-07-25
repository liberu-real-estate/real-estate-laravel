<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\PropertyMarketingService;

class PropertyMarketing extends Component
{
    public $property;
    public $syndicationResults = [];
    public $socialMediaPlatforms = ['facebook', 'twitter', 'instagram'];
    public $selectedPlatforms = [];

    protected $propertyMarketingService;

    public function mount(Property $property, PropertyMarketingService $propertyMarketingService)
    {
        $this->property = $property;
        $this->propertyMarketingService = $propertyMarketingService;
    }

    public function syndicateProperty()
    {
        $this->syndicationResults = $this->propertyMarketingService->syndicateProperty($this->property);
    }

    public function shareOnSocialMedia()
    {
        $this->propertyMarketingService->shareOnSocialMedia($this->property, $this->selectedPlatforms);
    }

    public function render()
    {
        return view('livewire.property-marketing');
    }
}