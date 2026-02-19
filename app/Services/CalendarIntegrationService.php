<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Appointment;
use Carbon\Carbon;

class CalendarIntegrationService
{
    /**
     * Generate an ICS (iCalendar) file content for a booking.
     */
    public function generateBookingIcs(Booking $booking): string
    {
        $start = Carbon::parse($booking->date->format('Y-m-d') . ' ' . ($booking->time ? $booking->time->format('H:i:s') : '10:00:00'));
        $end = $start->copy()->addHour();
        $title = 'Property Viewing: ' . ($booking->property->title ?? 'Property');
        $location = $booking->property->address ?? '';
        $description = 'Property viewing appointment.' . ($booking->notes ? ' Notes: ' . $booking->notes : '');

        return $this->buildIcs($title, $location, $description, $start, $end);
    }

    /**
     * Generate an ICS (iCalendar) file content for a valuation appointment.
     */
    public function generateAppointmentIcs(Appointment $appointment): string
    {
        $start = Carbon::parse($appointment->appointment_date);
        $end = $start->copy()->addHour();
        $typeName = $appointment->appointmentType->name ?? 'Appointment';
        $title = 'Property ' . $typeName . ': ' . ($appointment->property->title ?? ($appointment->property_address ?? 'Your Property'));
        $location = $appointment->property->address ?? $appointment->property_address ?? '';
        $description = $typeName . ' appointment.' . ($appointment->notes ? ' Notes: ' . $appointment->notes : '');

        return $this->buildIcs($title, $location, $description, $start, $end);
    }

    /**
     * Build Google Calendar quick-add URL for a booking.
     */
    public function getBookingGoogleCalendarUrl(Booking $booking): string
    {
        $start = Carbon::parse($booking->date->format('Y-m-d') . ' ' . ($booking->time ? $booking->time->format('H:i:s') : '10:00:00'));
        $end = $start->copy()->addHour();
        $title = 'Property Viewing: ' . ($booking->property->title ?? 'Property');
        $location = $booking->property->address ?? '';

        return $this->buildGoogleCalendarUrl($title, $location, $start, $end);
    }

    /**
     * Build Google Calendar quick-add URL for an appointment.
     */
    public function getAppointmentGoogleCalendarUrl(Appointment $appointment): string
    {
        $start = Carbon::parse($appointment->appointment_date);
        $end = $start->copy()->addHour();
        $typeName = $appointment->appointmentType->name ?? 'Appointment';
        $title = 'Property ' . $typeName . ': ' . ($appointment->property->title ?? ($appointment->property_address ?? 'Your Property'));
        $location = $appointment->property->address ?? $appointment->property_address ?? '';

        return $this->buildGoogleCalendarUrl($title, $location, $start, $end);
    }

    /**
     * Build Outlook Calendar URL for a booking.
     */
    public function getBookingOutlookCalendarUrl(Booking $booking): string
    {
        $start = Carbon::parse($booking->date->format('Y-m-d') . ' ' . ($booking->time ? $booking->time->format('H:i:s') : '10:00:00'));
        $end = $start->copy()->addHour();
        $title = 'Property Viewing: ' . ($booking->property->title ?? 'Property');
        $location = $booking->property->address ?? '';

        return $this->buildOutlookCalendarUrl($title, $location, $start, $end);
    }

    /**
     * Build Outlook Calendar URL for an appointment.
     */
    public function getAppointmentOutlookCalendarUrl(Appointment $appointment): string
    {
        $start = Carbon::parse($appointment->appointment_date);
        $end = $start->copy()->addHour();
        $typeName = $appointment->appointmentType->name ?? 'Appointment';
        $title = 'Property ' . $typeName . ': ' . ($appointment->property->title ?? ($appointment->property_address ?? 'Your Property'));
        $location = $appointment->property->address ?? $appointment->property_address ?? '';

        return $this->buildOutlookCalendarUrl($title, $location, $start, $end);
    }

    private function buildIcs(string $title, string $location, string $description, Carbon $start, Carbon $end): string
    {
        $uid = uniqid('', true) . '@' . parse_url(config('app.url'), PHP_URL_HOST);
        $now = Carbon::now()->utc()->format('Ymd\THis\Z');
        $startUtc = $start->utc()->format('Ymd\THis\Z');
        $endUtc = $end->utc()->format('Ymd\THis\Z');

        return "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "PRODID:-//Liberu Real Estate//EN\r\n" .
            "CALSCALE:GREGORIAN\r\n" .
            "METHOD:REQUEST\r\n" .
            "BEGIN:VEVENT\r\n" .
            "UID:{$uid}\r\n" .
            "DTSTAMP:{$now}\r\n" .
            "DTSTART:{$startUtc}\r\n" .
            "DTEND:{$endUtc}\r\n" .
            "SUMMARY:" . $this->escapeIcsText($title) . "\r\n" .
            "LOCATION:" . $this->escapeIcsText($location) . "\r\n" .
            "DESCRIPTION:" . $this->escapeIcsText($description) . "\r\n" .
            "END:VEVENT\r\n" .
            "END:VCALENDAR\r\n";
    }

    private function buildGoogleCalendarUrl(string $title, string $location, Carbon $start, Carbon $end): string
    {
        return 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => $title,
            'dates' => $start->utc()->format('Ymd\THis\Z') . '/' . $end->utc()->format('Ymd\THis\Z'),
            'details' => $title,
            'location' => $location,
        ]);
    }

    private function buildOutlookCalendarUrl(string $title, string $location, Carbon $start, Carbon $end): string
    {
        return 'https://outlook.live.com/calendar/0/deeplink/compose?' . http_build_query([
            'path' => '/calendar/action/compose',
            'rru' => 'addevent',
            'subject' => $title,
            'startdt' => $start->utc()->format('Y-m-d\TH:i:s\Z'),
            'enddt' => $end->utc()->format('Y-m-d\TH:i:s\Z'),
            'location' => $location,
        ]);
    }

    private function escapeIcsText(string $text): string
    {
        return str_replace(["\r\n", "\n", "\r", ',', ';', '\\'], ['\\n', '\\n', '\\n', '\\,', '\\;', '\\\\'], $text);
    }
}
