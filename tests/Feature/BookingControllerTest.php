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

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['property_id', 'user_id', 'start_date', 'end_date']);
    }