<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;
use App\Notifications\BookingNotification;

class VisitBookingService
{
    protected $notificationService;
    protected $calendarService;

    public function __construct(NotificationService $notificationService, CalendarIntegrationService $calendarService)
    {
        $this->notificationService = $notificationService;
        $this->calendarService = $calendarService;
    }
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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
        $booking = Booking::create($data);
        $this->scheduleReminder($booking);
        return $booking;
    }

    public function getUpcomingVisits()
    {
        return Booking::visits()->where('date', '>=', now())->orderBy('date')->get();
    }

    public function recordFeedback(Booking $booking, string $feedback)
    {
        $booking->update(['feedback' => $feedback]);
    }

    public function requestFeedback(Booking $booking)
    {
        $feedbackRequestTime = Carbon::parse($booking->date . ' ' . $booking->time)->addHours(2);
        $this->notificationService->scheduleNotification(
            $booking->user,
            new BookingNotification($booking, 'feedback_request'),
            $feedbackRequestTime
        );
    }

    public function scheduleReminder(Booking $booking)
    {
        $reminderTime = Carbon::parse($booking->date . ' ' . $booking->time)->subHours(24);
        $this->notificationService->scheduleNotification(
            $booking->user,
            new BookingNotification($booking, 'reminder'),
            $reminderTime
        );
    }
}