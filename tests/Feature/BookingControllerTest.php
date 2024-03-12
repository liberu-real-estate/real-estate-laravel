<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStoreActionWithValidData()
    {
        $data = [
            'property_id' => $this->faker->numberBetween(1, 50),
            'user_id' => $this->faker->numberBetween(1, 50),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => 'pending'
        ];

        $response = $this->post('/bookings', $data);

        $response->assertStatus(302); // Assuming redirect on success
        $this->assertDatabaseHas('bookings', $data);
    }

    public function testStoreActionWithInvalidData()
    {
        $response = $this->post('/bookings', []);

        $response->assertStatus(422); // Validation error status code
    }

    public function testUpdateActionWithValidData()
    {
        $booking = Booking::factory()->create();
        $updateData = ['status' => 'confirmed'];

        $response = $this->put("/bookings/{$booking->id}", $updateData);

        $response->assertStatus(302); // Assuming redirect on success
        $this->assertDatabaseHas('bookings', array_merge(['id' => $booking->id], $updateData));
    }

    public function testUpdateActionWithInvalidData()
    {
        $booking = Booking::factory()->create();
        $response = $this->put("/bookings/{$booking->id}", ['status' => null]);

        $response->assertStatus(422); // Validation error status code
    }

    public function testIndexAction()
    {
        $bookings = Booking::factory()->count(5)->create();

        $response = $this->get('/bookings');

        $response->assertStatus(200);
        $response->assertJson($bookings->toArray());
    }
}
