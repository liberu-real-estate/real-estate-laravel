<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentType;

class VirtualTourAppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointmentTypes = [
            [
                'name' => 'Live Virtual Tour',
                'description' => 'Schedule a live virtual tour with an agent who will guide you through the property in real-time via video call.',
            ],
            [
                'name' => 'Self-Guided Virtual Tour',
                'description' => 'Access the 3D virtual tour at your convenience and explore the property at your own pace.',
            ],
        ];

        foreach ($appointmentTypes as $type) {
            AppointmentType::firstOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description']]
            );
        }
    }
}
