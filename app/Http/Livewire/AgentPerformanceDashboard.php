<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class AgentPerformanceDashboard extends Component
{
    public $startDate;
    public $endDate;
    public $metrics;
    public $feedback;

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->updateMetrics();
    }

    public function updateMetrics()
    {
        $agent = Auth::user();

        $this->metrics = [
            'listings_added' => $this->getListingsAdded($agent),
            'properties_sold' => $this->getPropertiesSold($agent),
            'appointments_scheduled' => $this->getAppointmentsScheduled($agent),
            'average_rating' => $this->getAverageRating($agent),
        ];

        $this->feedback = $this->getClientFeedback($agent);
    }

    private function getListingsAdded(User $agent)
    {
        return Property::where('agent_id', $agent->id)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getPropertiesSold(User $agent)
    {
        return Property::where('agent_id', $agent->id)
            ->where('status', 'sold')
            ->whereBetween('sold_date', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getAppointmentsScheduled(User $agent)
    {
        return Appointment::where('agent_id', $agent->id)
            ->whereBetween('appointment_date', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getAverageRating(User $agent)
    {
        return Review::where('reviewable_id', $agent->id)
            ->where('reviewable_type', User::class)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->avg('rating') ?? 0;
    }

    private function getClientFeedback(User $agent)
    {
        return Review::where('reviewable_id', $agent->id)
            ->where('reviewable_type', User::class)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.agent-performance-dashboard');
    }
}