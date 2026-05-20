<?php

namespace Database\Seeders;

use App\Models\CommunityEvent;
use App\Models\Property;
use Illuminate\Database\Seeder;

class CommunityEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 general community events
        CommunityEvent::factory(20)->create();

        // Create property-specific events for the first 5 properties (if they exist)
        $properties = Property::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(5)
            ->get();

        foreach ($properties as $property) {
            CommunityEvent::factory(2)->forProperty($property)->create();
        }
    }
}
