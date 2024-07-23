<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ViewingRequestNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
                    ->subject('New Viewing Request')
                    ->line('A new viewing has been requested for the following property:')
                    ->line('Property: ' . $this->appointment->property->title)
                    ->line('Date: ' . $this->appointment->appointment_date->format('Y-m-d H:i'))
                    ->line('Client: ' . $this->appointment->user->name)
                    ->action('View Appointment', url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'property_id' => $this->appointment->property_id,
            'user_id' => $this->appointment->user_id,
            'appointment_date' => $this->appointment->appointment_date,
        ];
    }
}