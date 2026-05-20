<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;
use Livewire\WithPagination;

class CommunicationHub extends Component
{
    use WithPagination;

    public $message;
    public $recipient_id;

    protected $rules = [
        'message' => 'required|string|max:1000',
        'recipient_id' => 'required|exists:users,id',
    ];

    public function sendMessage()
    {
        $this->validate();

        Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $this->recipient_id,
            'content' => $this->message,
        ]);

        $this->reset('message');
        $this->emit('messageSent');
    }

    public function render()
    {
        $messages = Message::where(function ($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('recipient_id', auth()->id());
        })->latest()->paginate(10);

        return view('livewire.communication-hub', [
            'messages' => $messages,
        ]);
    }
}