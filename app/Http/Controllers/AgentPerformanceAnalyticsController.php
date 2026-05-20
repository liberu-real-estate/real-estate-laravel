<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentPerformanceAnalyticsController extends Controller
{
    public function index()
    {
        return view('agent-performance.index');
    }

    public function generateAgentMetrics(Request $request)
    {
        $agent = $request->user();
        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        $metrics = [
            'listings_added' => $this->getListingsAdded($agent, $startDate, $endDate),
            'properties_sold' => $this->getPropertiesSold($agent, $startDate, $endDate),
            'appointments_scheduled' => $this->getAppointmentsScheduled($agent, $startDate, $endDate),
            'average_rating' => $this->getAverageRating($agent, $startDate, $endDate),
        ];

        return response()->json($metrics);
    }

    private function getListingsAdded(User $agent, $startDate, $endDate)
    {
        return Property::where('agent_id', $agent->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getPropertiesSold(User $agent, $startDate, $endDate)
    {
        return Property::where('agent_id', $agent->id)
            ->where('status', 'sold')
            ->whereBetween('sold_date', [$startDate, $endDate])
            ->count();
    }

    private function getAppointmentsScheduled(User $agent, $startDate, $endDate)
    {
        return Appointment::where('agent_id', $agent->id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->count();
    }

    private function getAverageRating(User $agent, $startDate, $endDate)
    {
        return Review::where('reviewable_id', $agent->id)
            ->where('reviewable_type', User::class)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('rating');
    }

    public function getClientFeedback(Request $request)
    {
        $agent = $request->user();
        $feedback = Review::where('reviewable_id', $agent->id)
            ->where('reviewable_type', User::class)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($feedback);
    }
}