<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseAgreementReady extends Notification
{
    use Queueable;

    protected $leaseAgreementId;

    public function __construct($leaseAgreementId)
    {
        $this->leaseAgreementId = $leaseAgreementId;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your lease agreement is ready for signing.')
                    ->action('View Lease Agreement', url('/lease-agreements/' . $this->leaseAgreementId))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'lease_agreement_id' => $this->leaseAgreementId,
            'message' => 'Your lease agreement is ready for signing.',
        ];
    }
}