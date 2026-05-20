<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\CalendarIntegrationService;

class AppointmentController extends Controller
{
    public function downloadIcs(Appointment $appointment, CalendarIntegrationService $calendarService)
    {
        $icsContent = $calendarService->generateAppointmentIcs($appointment);

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="valuation-appointment.ics"',
        ]);
    }
}
