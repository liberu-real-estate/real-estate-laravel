<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_appointment()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $appointmentData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'appointment_date' => '2023-06-01 14:00:00',
            'status' => 'scheduled',
        ];

        $appointment = Appointment::create($appointmentData);

        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertDatabaseHas('appointments', [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_appointment_relationships()
    {
        $appointment = Appointment::factory()->create();

        $this->assertInstanceOf(Property::class, $appointment->property);
        $this->assertInstanceOf(User::class, $appointment->user);
    }

    public function test_appointment_scope_upcoming()
    {
        Appointment::factory()->create(['appointment_date' => now()->addDays(1)]);
        Appointment::factory()->create(['appointment_date' => now()->subDays(1)]);

        $upcomingAppointments = Appointment::upcoming()->get();

        $this->assertCount(1, $upcomingAppointments);
    }
}
