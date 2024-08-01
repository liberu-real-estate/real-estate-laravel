<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\User;

class LandlordReviewForm extends Component
{
    public $landlordId;
    public $rating = 5;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:500',
    ];

    public function submitReview()
    {
        $this->validate();

        $landlord = User::findOrFail($this->landlordId);

        if (!$landlord->hasRole('landlord')) {
            throw new \Exception('User is not a landlord');
        }

        Review::create([
            'user_id' => auth()->id(),
            'reviewable_id' => $this->landlordId,
            'reviewable_type' => User::class,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        $this->emit('reviewAdded');
    }

    public function render()
    {
        return view('livewire.landlord-review-form');
    }
}