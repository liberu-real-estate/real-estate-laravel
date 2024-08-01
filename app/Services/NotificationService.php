<?php

namespace App\Services;

use App\Models\User;
use App\Models\Lease;
use App\Models\Appointment;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseAgreementReady;
use App\Notifications\LeaseRenewalReminder;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentReminder;
use Illuminate\Notifications\Notification as BaseNotification;
use Carbon\Carbon;

use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function notifyLeaseAgreementReady(User $user, $leaseAgreementId)
    {
        Notification::send($user, new LeaseAgreementReady($leaseAgreementId));
    }

    public function scheduleNotification(User $user, BaseNotification $notification, Carbon $sendAt)
    {
        $user->notify((new $notification)->delay($sendAt));
    }

    public function sendSmsNotification(User $user, string $message)
    {
        $response = Http::post('https://api.twilio.com/2010-04-01/Accounts/' . config('services.twilio.account_sid') . '/Messages.json', [
            'From' => config('services.twilio.from_number'),
            'To' => $user->phone_number,
            'Body' => $message,
        ])->withBasicAuth(config('services.twilio.account_sid'), config('services.twilio.auth_token'));

        return $response->successful();
    }

    public function sendLeaseRenewalReminder(User $user, Lease $lease)
    {
        Notification::send($user, new LeaseRenewalReminder($lease));
    }

    public function notifyAppointmentCreated(User $user, Appointment $appointment)
    {
        Notification::send($user, new AppointmentCreated($appointment));
    }

    public function scheduleAppointmentReminder(Appointment $appointment)
    {
        $reminderTime = $appointment->appointment_date->subHours(24);
        $this->scheduleNotification($appointment->user, new AppointmentReminder($appointment), $reminderTime);
    }
}