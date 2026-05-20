<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRequestSubmitted extends Notification
{
    use Queueable;

    protected $maintenanceRequest;

    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your maintenance request has been submitted successfully.')
            ->line('Title: ' . $this->maintenanceRequest->title)
            ->line('Status: ' . $this->maintenanceRequest->status)
            ->action('View Request', url('/maintenance-requests/' . $this->maintenanceRequest->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'maintenance_request_id' => $this->maintenanceRequest->id,
            'title' => $this->maintenanceRequest->title,
            'status' => $this->maintenanceRequest->status,
        ];
    }
}