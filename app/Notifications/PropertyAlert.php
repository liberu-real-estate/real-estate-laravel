<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Property Alert')
                    ->line('A new property matching your criteria has been listed:')
                    ->line('Title: ' . $this->property->title)
                    ->line('Price: $' . number_format($this->property->price, 2))
                    ->line('Location: ' . $this->property->location)
                    ->action('View Property', url('/properties/' . $this->property->id));
    }

    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'title' => $this->property->title,
            'price' => $this->property->price,
            'location' => $this->property->location,
        ];
    }
}