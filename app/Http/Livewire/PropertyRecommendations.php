<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\PropertyRecommendationService;

class PropertyRecommendations extends Component
{
    public $recommendations = [];

    protected $listeners = ['updateRecommendations'];

    public function mount()
    {
        $this->updateRecommendations();
    }

    public function updateRecommendations()
    {
        $recommendationService = app(PropertyRecommendationService::class);
        $this->recommendations = $recommendationService->getRecommendations(auth()->user());
    }

    public function render()
    {
        return view('livewire.property-recommendations');
    }
}