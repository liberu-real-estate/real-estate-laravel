<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\RightMoveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class RightmoveApiController extends Controller
{
    protected $rightMoveService;

    public function __construct(RightMoveService $rightMoveService)
    {
        $this->rightMoveService = $rightMoveService;
    }

    public function fetchProperties()
    {
        try {
            $properties = $this->rightMoveService->fetchProperties();
            return response()->json($properties);
        } catch (Exception $e) {
            Log::error('Failed to fetch properties from RightMove: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch properties.'], 500);
        }
    }

    public function createListing(Request $request)
    {
        try {
            $property = Property::findOrFail($request->input('property_id'));
            $listing = $this->rightMoveService->createListing($property);
            return response()->json($listing);
        } catch (Exception $e) {
            Log::error('Failed to create listing on RightMove: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create listing.'], 500);
        }
    }

    public function updateListing(Request $request, $listingId)
    {
        try {
            $property = Property::findOrFail($request->input('property_id'));
            $listing = $this->rightMoveService->updateListing($listingId, $property);
            return response()->json($listing);
        } catch (Exception $e) {
            Log::error('Failed to update listing on RightMove: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update listing.'], 500);
        }
    }

    public function syncAllProperties()
    {
        try {
            $result = $this->rightMoveService->syncAllProperties();
            return response()->json(['message' => 'All properties synced successfully', 'result' => $result]);
        } catch (Exception $e) {
            Log::error('Failed to sync all properties with RightMove: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to sync all properties.'], 500);
        }
    }

    public function syncProperty($propertyId)
    {
        try {
            $property = Property::findOrFail($propertyId);
            $result = $this->rightMoveService->syncProperty($property);
            return response()->json(['message' => 'Property synced successfully', 'result' => $result]);
        } catch (Exception $e) {
            Log::error('Failed to sync property with RightMove: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to sync property.'], 500);
        }
    }
}
