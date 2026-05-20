<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\PropertyRecommendationService;

class PropertyRecommendations extends Component
{
    public $recommendations = [];
    public $limit = 6;

    protected $listeners = ['updateRecommendations', 'loadMore'];

    public function mount()
    {
        $this->updateRecommendations();
    }

    public function updateRecommendations()
    {
        $recommendationService = app(PropertyRecommendationService::class);
        $this->recommendations = $recommendationService->getRecommendations(auth()->user(), $this->limit);
    }

    public function loadMore()
    {
        $this->limit += 6;
        $this->updateRecommendations();
    }

    public function render()
    {
        return view('livewire.property-recommendations');
    }
}