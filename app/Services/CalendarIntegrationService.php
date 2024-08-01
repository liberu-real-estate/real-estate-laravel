<?php

namespace App\Services;

use App\Models\Booking;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class CalendarIntegrationService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(config('services.google.calendar_credentials'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        
        $this->service = new Google_Service_Calendar($this->client);
    }

    public function addBookingToCalendar(Booking $booking)
    {
        $event = new Google_Service_Calendar_Event([
            'summary' => 'Property Visit: ' . $booking->property->title,
            'location' => $booking->property->location,
            'description' => 'Visit for property: ' . $booking->property->title,
            'start' => [
                'dateTime' => $booking->date->format('Y-m-d') . 'T' . $booking->time->format('H:i:s'),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'dateTime' => $booking->date->format('Y-m-d') . 'T' . $booking->time->addHour()->format('H:i:s'),
                'timeZone' => config('app.timezone'),
            ],
        ]);

        $calendarId = 'primary';
        $this->service->events->insert($calendarId, $event);
    }
}