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
            'notes' => 'nullable|string'
        ]);

        $booking = Booking::create($validated);

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    public function update(Request $request, $id)
    /**
     * Stores a new booking in the database.
     * 
     * @param Request $request The HTTP request containing the booking data.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'staff_id' => 'required|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string'
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
/**
 * BookingController handles booking-related actions such as storing and updating bookings.
 */
}
    /**
     * Retrieves all bookings from the database.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing all bookings.
     */
    /**
     * Updates an existing booking in the database.
     * 
     * @param Request $request The HTTP request containing the updated booking data.
     * @param int $id The ID of the booking to update.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
