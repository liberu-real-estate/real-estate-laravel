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
    /**
    * Displays the home page with featured properties.
    *
    * @return \Illuminate\View\View The view of the home page with featured properties.
    */
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
