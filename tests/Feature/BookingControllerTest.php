<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

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

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success', 'Booking created successfully.');
        $this->assertDatabaseHas('bookings', $data);
    }

    public function testStoreActionWithInvalidData()
    {
        $response = $this->post('/bookings', []);
    
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['date', 'time', 'staff_id']);
    }
}