<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Neighborhood;
use Illuminate\Support\Facades\Auth;

class NeighborhoodReviewForm extends Component
{
    public $neighborhoodId;
    public $rating = 5;
    public $comment = '';
    public $title = '';
    public Neighborhood $neighborhood;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
        'title' => 'required|string|min:3|max:100',
    ];

    public function mount($neighborhoodId)
    {
        $this->neighborhoodId = $neighborhoodId;
        $this->neighborhood = Neighborhood::findOrFail($neighborhoodId);
    }

    public function submitReview()
    {
        $this->validate();

        // Check if user has already reviewed this neighborhood
        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_id', $this->neighborhoodId)
            ->where('reviewable_type', Neighborhood::class)
            ->first();

        if ($existingReview) {
            $this->addError('general', 'You have already reviewed this neighborhood.');
            return;
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewable_id' => $this->neighborhoodId,
            'reviewable_type' => Neighborhood::class,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'title' => $this->title,
            'review_date' => now(),
            'ip_address' => request()->ip(),
            'moderation_status' => 'pending',
            'approved' => false,
        ]);

        $this->reset(['rating', 'comment', 'title']);
        $this->rating = 5; // Reset to default
        
        session()->flash('message', 'Thank you for your review! It will be published after moderation.');
        
        $this->emit('reviewAdded', $review->id);
    }

    public function render()
    {
        return view('livewire.neighborhood-review-form');
    }
}
