<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\AgentMatchingService;

class AgentRecommendations extends Component
{
    public $recommendations = [];
    public $limit = 5;
    public $searchContext = [];

    protected $listeners = ['updateRecommendations', 'updateSearchContext'];

    public function mount($searchContext = [])
    {
        $this->searchContext = $searchContext;
        $this->updateRecommendations();
    }

    public function updateRecommendations()
    {
        $agentMatchingService = app(AgentMatchingService::class);
        
        if (!empty($this->searchContext)) {
            // Get agents based on property search context
            $this->recommendations = $agentMatchingService->getRecommendedAgentsForPropertySearch(
                auth()->user(), 
                $this->searchContext
            );
        } else {
            // Get general agent recommendations
            $this->recommendations = $agentMatchingService->findMatches(
                auth()->user(), 
                $this->limit
            );
        }
    }

    public function updateSearchContext($context)
    {
        $this->searchContext = $context;
        $this->updateRecommendations();
    }

    public function generateMatches()
    {
        $agentMatchingService = app(AgentMatchingService::class);
        $agentMatchingService->generateMatchesForUser(auth()->user());
        $this->updateRecommendations();
        
        $this->emit('matchesGenerated');
    }

    public function render()
    {
        return view('livewire.agent-recommendations');
    }
}
