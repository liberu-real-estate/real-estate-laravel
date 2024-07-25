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
        $message = (new MailMessage)
            ->subject("Lease {$this->type} Notification");

        if ($this->type === 'renewal') {
            $message->line("Your lease for {$this->lease->property->address} is up for renewal.")
                    ->action('Renew Lease', url("/leases/{$this->lease->id}/renew"))
                    ->line("The current lease ends on {$this->lease->end_date->format('F d, Y')}.");
        } elseif ($this->type === 'termination') {
            $message->line("Your lease for {$this->lease->property->address} has been terminated.")
                    ->line("The lease termination date is {$this->lease->end_date->format('F d, Y')}.")
                    ->line('Please contact the property management for more information.');
        }

        return $message;
    }
}