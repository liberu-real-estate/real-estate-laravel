<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Favorite;
use App\Services\NeighborhoodDataService;
use Illuminate\Support\Facades\Auth;

use App\Models\Lead;
use App\Services\LeadScoringService;

class PropertyDetail extends Component
{
    public $property;
    public $neighborhood;
    public $team;
    public $isLettingsProperty;
    public $reviews;
    public $neighborhoodData;
    public $showInvestmentSimulation = false;
    public $isFavorited = false;

    // Lead capture form fields
    public $name;
    public $email;
    public $phone;
    public $message;

    protected $neighborhoodDataService;
    protected $leadScoringService;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'message' => 'nullable|string',
    ];

    public function boot(NeighborhoodDataService $neighborhoodDataService, LeadScoringService $leadScoringService)
    {
        $this->neighborhoodDataService = $neighborhoodDataService;
        $this->leadScoringService = $leadScoringService;
    }

    public function mount($propertyId)
    {
        $this->property = Property::with(['neighborhood', 'features', 'team', 'category', 'reviews.user'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
        $this->team = $this->property->team;
        $this->isLettingsProperty = $this->property->category->name === 'lettings';
        $this->reviews = $this->property->reviews()->with('user')->latest()->get();

        // Check if property is favorited by current user
        if (Auth::check()) {
            $this->isFavorited = Favorite::where('user_id', Auth::id())
                ->where('property_id', $this->property->id)
                ->exists();
        }

        $this->updateNeighborhoodData();
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('property_id', $this->property->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $this->isFavorited = false;
            session()->flash('message', 'Property removed from wishlist');
            $this->emit('favoriteRemoved');
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'property_id' => $this->property->id,
                'team_id' => $user->currentTeam?->id,
            ]);
            $this->isFavorited = true;
            session()->flash('message', 'Property added to wishlist');
            $this->emit('favoriteAdded');
        }
    }

    public function render()
    {
        return view('livewire.property-detail', [
            'investmentAnalysisComponent' => $this->showInvestmentSimulation ? new InvestmentAnalysisComponent($this->property) : null,
        ])->layout('layouts.app');
    }

    public function toggleInvestmentSimulation()
    {
        $this->showInvestmentSimulation = !$this->showInvestmentSimulation;
    }

    public function submitLeadForm()
    {
        $this->validate();

        $lead = Lead::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'interest' => $this->isLettingsProperty ? 'renting' : 'buying',
            'status' => 'new',
            'team_id' => $this->team->id,
        ]);

        $lead->addActivity('property_inquiry', "Inquired about property {$this->property->id}");

        $this->leadScoringService->updateLeadScore($lead);

        $this->reset(['name', 'email', 'phone', 'message']);

        session()->flash('message', 'Thank you for your inquiry. We will contact you soon!');
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
