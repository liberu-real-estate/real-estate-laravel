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
    public $neighborhoodReviews;
    public $neighborhoodAverageRating;
    public $neighborhoodData;
    public $showInvestmentSimulation = false;
    public $propertyHistory = [];
    public $priceHistory = [];
    public $salesHistory = [];
    public $isFavorited = false;
    public $investmentAnalytics = null;
    public $communityEvents = [];
    public $selectedMonth;
    public $selectedYear;

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
        $this->property = Property::with(['neighborhood', 'features', 'team', 'category', 'reviews.user', 'histories'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
        $this->team = $this->property->team;
        $this->isLettingsProperty = $this->property->category && $this->property->category->name === 'lettings';
        $this->reviews = $this->property->reviews()->with('user')->latest()->get();
        
        // Load property history
        $this->propertyHistory = $this->property->histories()->take(10)->get();
        $this->priceHistory = $this->property->histories()->priceChanges()->take(5)->get();
        $this->salesHistory = $this->property->histories()->sales()->get();
        // Load neighborhood reviews if neighborhood exists
        if ($this->neighborhood) {
            $this->neighborhoodReviews = $this->neighborhood->reviews()
                ->where('approved', true)
                ->with('user')
                ->latest()
                ->get();
            
            // Compute average rating once to avoid N+1 queries in the view
            $this->neighborhoodAverageRating = $this->neighborhoodReviews->avg('rating') ?? 0;
        }

        // Check if property is favorited by current user
        if (Auth::check()) {
            $this->isFavorited = Favorite::where('user_id', Auth::id())
                ->where('property_id', $this->property->id)
                ->exists();
        }

        // Initialize calendar month/year
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;

        $this->updateNeighborhoodData();
        $this->updateWalkabilityScores();
        $this->loadInvestmentAnalytics();
        $this->loadCommunityEvents();
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
        return view('livewire.property-detail')->layout('layouts.app');
    }

    public function toggleInvestmentSimulation()
    {
        $this->showInvestmentSimulation = !$this->showInvestmentSimulation;
    }
    
    public function loadInvestmentAnalytics()
    {
        try {
            $aiInvestmentService = app(\App\Services\AIInvestmentAnalysisService::class);
            $this->investmentAnalytics = $aiInvestmentService->analyzeInvestment($this->property);
        } catch (\Exception $e) {
            \Log::error('Failed to load investment analytics: ' . $e->getMessage());
            $this->investmentAnalytics = null;
        }
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
    
    public function getPositionBadgeClass($position)
    {
        return match($position) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'average' => 'bg-gray-100 text-gray-800',
            'above_average' => 'bg-yellow-100 text-yellow-800',
            'premium' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
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

    public function updateWalkabilityScores()
    {
        // Update walkability scores if they're missing or outdated
        if ($this->property->needsWalkabilityUpdate()) {
            $this->property->updateWalkabilityScores();
            $this->property->refresh();
        }
    }

    public function loadCommunityEvents()
    {
        // Load events relevant to the property location
        $this->communityEvents = $this->property->getNearbyCommunityEvents(10);
    }

    public function changeMonth($direction)
    {
        if ($direction === 'next') {
            if ($this->selectedMonth === 12) {
                $this->selectedMonth = 1;
                $this->selectedYear++;
            } else {
                $this->selectedMonth++;
            }
        } else {
            if ($this->selectedMonth === 1) {
                $this->selectedMonth = 12;
                $this->selectedYear--;
            } else {
                $this->selectedMonth--;
            }
        }
    }

    public function getEventsForCalendar()
    {
        return $this->communityEvents->filter(function ($event) {
            return $event->event_date->month === $this->selectedMonth &&
                   $event->event_date->year === $this->selectedYear;
        });
    }
}
