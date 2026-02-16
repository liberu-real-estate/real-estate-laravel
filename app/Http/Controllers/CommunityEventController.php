<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use App\Models\Property;
use Illuminate\Http\Request;

class CommunityEventController extends Controller
{
    /**
     * Display a listing of community events.
     */
    public function index(Request $request)
    {
        $query = CommunityEvent::query()->public()->upcoming();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->category($request->category);
        }

        // Filter by location if provided
        if ($request->has('latitude') && $request->has('longitude')) {
            $radius = $request->get('radius', 10);
            $query->nearby($request->latitude, $request->longitude, $radius);
        }

        // Filter by property if provided
        if ($request->has('property_id') && $request->property_id) {
            $property = Property::findOrFail($request->property_id);
            if ($property->latitude && $property->longitude) {
                $radius = $request->get('radius', 10);
                $query->nearby($property->latitude, $property->longitude, $radius);
            }
        }

        $events = $query->paginate(20);

        return response()->json($events);
    }

    /**
     * Display the specified community event.
     */
    public function show($id)
    {
        $event = CommunityEvent::findOrFail($id);
        
        if (!$event->is_public && !auth()->check()) {
            abort(403, 'This event is private.');
        }

        return response()->json($event);
    }

    /**
     * Get events for a specific property.
     */
    public function propertyEvents($propertyId, Request $request)
    {
        $property = Property::findOrFail($propertyId);
        $radius = $request->get('radius', 10);
        
        $events = $property->getNearbyCommunityEvents($radius);

        return response()->json($events);
    }
}
