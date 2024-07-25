<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lease;

class LeaseNotification extends Notification
{
    use Queueable;

    protected $lease;
    protected $type;

    public function __construct(Lease $lease, string $type)
    {
        $this->lease = $lease;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)->subject('Lease Notification');

        switch ($this->type) {
            case 'renewal':
                $message->line('Your lease is up for renewal.')
                        ->action('View Lease', url('/leases/' . $this->lease->id))
                        ->line('Please contact us to discuss renewal options.');
                break;
            case 'termination':
                $message->line('Your lease has been terminated.')
                        ->action('View Lease', url('/leases/' . $this->lease->id))
                        ->line('Please ensure all move-out procedures are followed.');
                break;
        }

        return $message;
    }
}