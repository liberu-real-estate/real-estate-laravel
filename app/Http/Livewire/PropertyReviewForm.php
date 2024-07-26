<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;

class PropertyReviewForm extends Component
{
    public $propertyId;
    public $rating = 5;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:500',
    ];

    public function submitReview()
    {
        $this->validate();

        Review::create([
            'user_id' => auth()->id(),
            'property_id' => $this->propertyId,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        $this->emit('reviewAdded');
    }

    public function render()
    {
        return view('livewire.property-review-form');
    }
}