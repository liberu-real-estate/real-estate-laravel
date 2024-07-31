<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $properties = Property::where('title', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title']);

        return response()->json($properties);
    }
}