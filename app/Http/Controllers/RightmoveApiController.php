<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class RightmoveApiController extends Controller
{
    protected $baseUri = 'https://api.rightmove.com';

    public function fetchProperties()
    {
        try {
            $response = Http::get("{$this->baseUri}/properties");
            return $response->json();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch properties.'], 500);
        }
    }

    public function createListing(Request $request)
    {
        try {
            $response = Http::post("{$this->baseUri}/listings", $request->all());
            return $response->json();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create listing.'], 500);
        }
    }

    public function updateListing(Request $request, $listingId)
    {
        try {
            $response = Http::put("{$this->baseUri}/listings/{$listingId}", $request->all());
            return $response->json();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update listing.'], 500);
        }
    }
}
