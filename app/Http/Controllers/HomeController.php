<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProperties = Property::where('is_featured', true)->take(3)->get() ?? [];

        $mapProperties = Property::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->price,
                    'latitude' => $property->latitude,
                    'longitude' => $property->longitude,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'area_sqft' => $property->area_sqft,
                ];
            });

        Log::info('Map properties count: ' . $mapProperties->count());

        return view('home', [
            'featuredProperties' => $featuredProperties,
            'mapProperties' => $mapProperties
        ]);
    }
}
