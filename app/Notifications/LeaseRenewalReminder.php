<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseRenewalReminder extends Notification
{
    use Queueable;

    protected $lease;

    public function __construct(Lease $lease)
    {
        $this->lease = $lease;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lease Renewal Reminder')
            ->line('Your lease for ' . $this->lease->property->address . ' is up for renewal.')
            ->action('View Lease Details', url('/tenant/leases/' . $this->lease->id))
            ->line('Please contact us to discuss renewal options.');
    }

    public function toArray($notifiable)
    {
        return [
            'lease_id' => $this->lease->id,
            'property_address' => $this->lease->property->address,
            'end_date' => $this->lease->end_date->toDateString(),
        ];
    }
}