<?php

namespace App\Http\Controllers;

use App\Services\RecommendationEngineService;
use Illuminate\Http\Request;

class RecommendationEngineController extends Controller
{
    protected $recommendationEngineService;

    public function __construct(RecommendationEngineService $recommendationEngineService)
    {
        $this->recommendationEngineService = $recommendationEngineService;
    }

    public function getRecommendations(Request $request)
    {
        $user = $request->user();
        $recommendations = $this->recommendationEngineService->getRecommendations($user);

        return view('recommendations', compact('recommendations'));
    }

    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'property_type' => 'nullable|string',
            // Add more fields as needed
        ]);

        $this->recommendationEngineService->updateUserPreferences($user, $data);

        return redirect()->back()->with('success', 'Preferences updated successfully');
    }
}