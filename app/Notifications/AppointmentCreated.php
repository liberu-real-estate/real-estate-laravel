<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreated extends Notification implements ShouldQueue
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
            ->subject("{$typeName} Appointment Confirmed")
            ->greeting("Hello {$this->appointment->name}!")
            ->line("Your {$typeName} appointment has been received and is being processed.")
            ->line("Date: {$date}")
            ->line("Time: {$time}")
            ->line('Our team will be in touch shortly to confirm the details.')
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
