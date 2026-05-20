<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Tenant;

class TenantReviewForm extends Component
{
    public $tenantId;
    public $rating = 5;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:500',
    ];

    public function submitReview()
    {
        $this->validate();

        $tenant = Tenant::findOrFail($this->tenantId);

        Review::create([
            'user_id' => auth()->id(),
            'reviewable_id' => $this->tenantId,
            'reviewable_type' => Tenant::class,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        $this->emit('reviewAdded');
    }

    public function render()
    {
        return view('livewire.tenant-review-form');
    }
}