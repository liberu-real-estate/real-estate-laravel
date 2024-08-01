<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\NeighborhoodDataService;

class PropertyDetail extends Component
{
    public $property;
    public $neighborhood;
    public $team;
    public $isLettingsProperty;
    public $reviews;
    public $neighborhoodData;

    protected $neighborhoodDataService;

    public function boot(NeighborhoodDataService $neighborhoodDataService)
    {
        $this->neighborhoodDataService = $neighborhoodDataService;
    }

    public function mount($propertyId)
    {
        $this->property = Property::with(['neighborhood', 'features', 'team', 'category', 'reviews.user'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
        $this->team = $this->property->team;
        $this->isLettingsProperty = $this->property->category->name === 'lettings';
        $this->reviews = $this->property->reviews()->with('user')->latest()->get();

        $this->updateNeighborhoodData();
    }

    public function render()
    {
        return view('livewire.property-detail')->layout('layouts.app');
    }

    public function getEnergyRatingColor($rating)
    {
        $colors = [
            'A' => '#00a651',
            'B' => '#50b848',
            'C' => '#aed136',
            'D' => '#fff200',
            'E' => '#fdb913',
            'F' => '#f37021',
            'G' => '#ed1c24',
        ];

        return $colors[$rating] ?? '#808080'; // Default to gray if rating not found
    }

    public function updateNeighborhoodData()
    {
        if ($this->neighborhood) {
            $zipCode = $this->property->postal_code;
            $freshData = $this->neighborhoodDataService->getNeighborhoodData($zipCode);

            if ($freshData) {
                $this->neighborhood->update([
                    'median_income' => $freshData['median_income'],
                    'population' => $freshData['population'],
                    'walk_score' => $freshData['walk_score'],
                    'transit_score' => $freshData['transit_score'],
                    'last_updated' => now(),
                ]);

                $this->neighborhood->refresh();
            }
        }
    }
}
