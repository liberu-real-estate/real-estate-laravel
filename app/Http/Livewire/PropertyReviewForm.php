<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyReviewForm extends Component
{
    public $propertyId;
    public $rating = 5;
    public $comment = '';
    public $title = '';
    public Property $property;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
        'title' => 'required|string|min:3|max:100',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->property = Property::findOrFail($propertyId);
    }

    public function submitReview()
    {
        $this->validate();

        if (!$this->canUserReview()) {
            $this->addError('general', 'You are not eligible to review this property.');
            return;
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewable_id' => $this->propertyId,
            'reviewable_type' => Property::class,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'title' => $this->title,
            'ip_address' => request()->ip(),
            'moderation_status' => 'pending',
            'approved' => false,
        ]);

        $this->reset(['rating', 'comment', 'title']);
        $this->emit('reviewAdded', $review->id);
    }

    public function canUserReview()
    {
        $user = Auth::user();
        // Check if the user has interacted with the property (e.g., booked, rented)
        return $user->bookings()->where('property_id', $this->propertyId)->exists() ||
               $user->rentals()->where('property_id', $this->propertyId)->exists();
    }

    public function render()
    {
        return view('livewire.property-review-form');
    }
}