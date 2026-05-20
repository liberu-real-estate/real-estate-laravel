<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\CalendarIntegrationService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {

    try {
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
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
        }

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

    public function downloadIcs(Booking $booking, CalendarIntegrationService $calendarService)
    {
        $icsContent = $calendarService->generateBookingIcs($booking);

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="property-viewing.ics"',
        ]);
    }
}
