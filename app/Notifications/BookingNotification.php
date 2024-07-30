
    {
        return (new MailMessage)
                    ->subject("Booking {$this->action}")
                    ->line("Your booking for {$this->booking->property->title} has been {$this->action}.")
                    ->line("Date: {$this->booking->date->format('F j, Y')}")
                    ->line("Time: {$this->booking->time->format('g:i A')}");
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'action' => $this->action,
            'property' => $this->booking->property->title,
            'date' => $this->booking->date->format('Y-m-d'),
            'time' => $this->booking->time->format('H:i'),
        ];
    }
}