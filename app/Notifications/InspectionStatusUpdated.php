<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inspection;

class InspectionStatusUpdated extends Notification
{
    use Queueable;

    protected $inspection;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The inspection status has been updated.')
                    ->line('Property: ' . $this->inspection->property->title)
                    ->line('New Status: ' . ucfirst($this->inspection->status))
                    ->line('Scheduled At: ' . $this->inspection->scheduled_at->format('Y-m-d H:i'))
                    ->action('View Inspection', url('/inspections/' . $this->inspection->id))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'inspection_id' => $this->inspection->id,
            'property_id' => $this->inspection->property_id,
            'status' => $this->inspection->status,
            'scheduled_at' => $this->inspection->scheduled_at,
        ];
    }
}