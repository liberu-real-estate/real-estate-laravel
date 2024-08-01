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

use App\Models\Lead;
use App\Notifications\LeadFollowUp;
use App\Notifications\LeadReminder;

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

    public function sendLeadFollowUp(Lead $lead)
    {
        $user = $lead->team->users->first(); // Assuming the first user in the team is responsible
        Notification::send($user, new LeadFollowUp($lead));
        $lead->markContacted();
    }

    public function scheduleLeadReminder(Lead $lead, $days = 7)
    {
        $user = $lead->team->users->first();
        $reminderTime = now()->addDays($days);
        $this->scheduleNotification($user, new LeadReminder($lead), $reminderTime);
    }

    public function sendAutomatedLeadEmails()
    {
        $leads = Lead::where('status', 'new')
            ->orWhere(function ($query) {
                $query->where('status', 'contacted')
                    ->where('last_contacted_at', '<=', now()->subDays(7));
            })
            ->get();

        foreach ($leads as $lead) {
            if ($lead->status === 'new') {
                $this->sendLeadFollowUp($lead);
            } else {
                $this->scheduleLeadReminder($lead);
            }
        }
    }
}