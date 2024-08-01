<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;

class VisitBookingService
{
    public function getAvailableTimeSlots(Property $property, $date)
    {
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        $bookedSlots = Booking::where('property_id', $property->id)
            ->whereDate('date', $date)
            ->pluck('time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        return array_diff($workingHours, $bookedSlots);
    }

    public function createVisit(array $data)
    {
        $data['visit_type'] = 'property_visit';
        return Booking::create($data);
    }

    public function getUpcomingVisits()
    {
        return Booking::visits()->where('date', '>=', now())->orderBy('date')->get();
    }

    public function recordFeedback(Booking $booking, string $feedback)
    {
        $booking->update(['feedback' => $feedback]);
    }
}