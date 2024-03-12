<?php

namespace App\Http\Controllers;

use App\Models\Viewing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViewingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'viewing_date' => 'required|date|after:now',
        ]);

        if (!$this->checkAvailability($request)) {
            return response()->json(['message' => 'The requested time is not available.'], Response::HTTP_CONFLICT);
        }

        $viewing = Viewing::create($validated);

        return response()->json(['message' => 'Viewing appointment booked successfully.', 'viewing_id' => $viewing->id], Response::HTTP_CREATED);
    }

    protected function checkAvailability(Request $request)
    {
        $existingViewing = Viewing::where('property_id', $request->property_id)
                                  ->where('viewing_date', $request->viewing_date)
                                  ->first();

        return is_null($existingViewing);
    }
}
