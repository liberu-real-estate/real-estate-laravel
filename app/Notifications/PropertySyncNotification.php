<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertySyncNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $message;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Property Sync Status')
                    ->line('The property sync process has completed.')
                    ->line('Status: ' . $this->status)
                    ->line('Message: ' . $this->message);
    }

    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}