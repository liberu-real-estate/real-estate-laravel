<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    
    public function about()
    {
        return view('about');
    }

    public function privacy()
    {
        return view('privacy-policy');
    }

    public function terms()
    {
        return view('terms-and-conditions');
    }

    public function services()
    {
        return view('services');
    }

    public function calculators()
    {
        return view('calculators');
    }
}
