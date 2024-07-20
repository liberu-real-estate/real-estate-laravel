<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProperties = Property::where('is_featured', true)->take(3)->get();
        $properties = Property::latest()->take(6)->get();
        return view('home', [
            'featuredProperties' => $featuredProperties,
            'properties' => $properties
        ]);
    }
}
