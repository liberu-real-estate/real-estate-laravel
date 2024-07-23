<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            DefaultTeamSeeder::class,
            PropertyCategorySeeder::class,
            PropertySeeder::class,
            AppointmentTypeSeeder::class,
            // Add other seeders here if needed
        ]);
    }
}
