<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\RecommendationEngineService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $recommendationEngineService;

    public function __construct(RecommendationEngineService $recommendationEngineService)
    {
        $this->recommendationEngineService = $recommendationEngineService;
    }

    public function index(Request $request)
    {
        // Existing property listing logic
        $properties = Property::paginate(15);

        if ($request->user()) {
            $this->recommendationEngineService->updateSearchHistory($request->user(), $request->all());
        }

        return view('properties.index', compact('properties'));
    }

    public function show(Request $request, Property $property)
    {
        if ($request->user()) {
            $this->recommendationEngineService->updateBrowsingBehavior($request->user(), $property);
        }

        return view('properties.show', compact('property'));
    }

    // Other methods...
}