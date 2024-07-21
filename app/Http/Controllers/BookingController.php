<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'staff_id' => 'required|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'contact' => 'nullable|string|max:255'
        ]);

        $booking = Booking::create($validated);

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'staff_id' => 'required|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'contact' => 'nullable|string|max:255'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update($validated);

        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking], 200);
    }

    public function index()
    {
        $bookings = Booking::all();
        return response()->json(['bookings' => $bookings], 200);
    }
}
