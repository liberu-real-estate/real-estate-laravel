<?php

namespace Database\Seeders;

use App\Models\ComponentSettings;
use Illuminate\Database\Seeder;

class ComponentSettingsSeeder extends Seeder
{
    public function run()
    {
        $components = [
            'property-booking',
            'property-map',
            'valuation-booking',
            'advanced-property-search',
            // Add other Livewire components here
        ];

        foreach ($components as $component) {
            ComponentSettings::create([
                'component_name' => $component,
                'is_enabled' => true,
            ]);
        }
    }
}
