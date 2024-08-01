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

    protected $properties;
    protected $isPersonalized;

    public function __construct($properties, $isPersonalized = false)
    {
        $this->properties = is_array($properties) ? $properties : [$properties];
        $this->isPersonalized = $isPersonalized;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->isPersonalized ? 'Personalized Property Recommendations' : 'New Property Alert')
            ->line($this->isPersonalized ? 'Here are some properties we think you might like:' : 'New properties matching your criteria have been listed:');

        foreach ($this->properties as $property) {
            $message->line('Title: ' . $property->title)
                    ->line('Price: $' . number_format($property->price, 2))
                    ->line('Location: ' . $property->location)
                    ->line('---');
        }

        $message->action('View All Properties', url('/properties'));

        return $message;
    }

    public function toArray($notifiable)
    {
        return array_map(function ($property) {
            return [
                'property_id' => $property->id,
                'title' => $property->title,
                'price' => $property->price,
                'location' => $property->location,
            ];
        }, $this->properties);
    }
}