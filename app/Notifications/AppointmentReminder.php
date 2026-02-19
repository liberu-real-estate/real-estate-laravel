<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $typeName = $this->appointment->appointmentType->name ?? 'Appointment';
        $date = $this->appointment->appointment_date->format('F j, Y');
        $time = $this->appointment->appointment_date->format('g:i A');

        return (new MailMessage)
            ->subject("{$typeName} Appointment Reminder")
            ->greeting("Hello {$this->appointment->name}!")
            ->line("This is a reminder for your upcoming {$typeName} appointment.")
            ->line("Date: {$date}")
            ->line("Time: {$time}")
            ->line('Please contact us if you need to reschedule.')
            ->salutation('Kind regards, The Real Estate Team');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->getKey(),
            'type' => $this->appointment->appointmentType->name ?? 'Appointment',
            'date' => $this->appointment->appointment_date->format('Y-m-d'),
            'time' => $this->appointment->appointment_date->format('H:i'),
        ];
    }
}
