<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected $priceDifference;

    public function __construct(Property $property, $priceDifference)
    {
        $this->property = $property;
        $this->priceDifference = $priceDifference;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $changeType = $this->priceDifference > 0 ? 'increased' : 'decreased';
        $absoluteDifference = abs($this->priceDifference);

        return (new MailMessage)
            ->subject("Price Alert: {$this->property->title}")
            ->line("The price of the property you're watching has {$changeType} by {$absoluteDifference}%.")
            ->line("New price: $" . number_format($this->property->price, 2))
            ->action('View Property', url("/properties/{$this->property->id}"));
    }

    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'title' => $this->property->title,
            'price_difference' => $this->priceDifference,
            'new_price' => $this->property->price,
        ];
    }
}