/**
 * HomeController handles the requests for the home page of the real estate application.
 * It retrieves featured properties and returns the corresponding view.
 */
<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProperties = Property::where('is_featured', true)->get();
        return view('home', ['featuredProperties' => $featuredProperties]);
    }
}
    {
        $featuredProperties = Property::where('is_featured', true)->get();
        return view('home', ['featuredProperties' => $featuredProperties]);
    }
}
