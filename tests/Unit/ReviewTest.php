<?php

namespace Tests\Unit;

use App\Models\Review;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_review()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $reviewData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Great property!',
        ];

        $review = Review::create($reviewData);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertDatabaseHas('reviews', $reviewData);
    }

    public function test_review_relationships()
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(Property::class, $review->property);
        $this->assertInstanceOf(User::class, $review->user);
    }

    public function test_review_scopes()
    {
        Review::factory()->create(['rating' => 5]);
        Review::factory()->create(['rating' => 3]);

        $this->assertCount(1, Review::where('rating', '>', 4)->get());
        $this->assertCount(2, Review::all());
    }
}