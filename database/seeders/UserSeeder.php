<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultTeam = Team::where('name', 'default')->first();

        if (!$defaultTeam) {
            throw new Exception('Default team not found. Please run the DefaultTeamSeeder first.');
        }

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('admin');

        $staffUser = User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $staffUser->assignRole('staff');

        // Assign admin and staff users to the default team
        $this->assignUserToDefaultTeam($adminUser, $defaultTeam);
        $this->assignUserToDefaultTeam($staffUser, $defaultTeam);

        // Create additional users and assign them to the default team
        User::factory(8)->create()->each(function ($user) use ($defaultTeam) {
            $this->assignUserToDefaultTeam($user, $defaultTeam);
        });
    }

    private function assignUserToDefaultTeam($user, $defaultTeam)
    {
        $user->teams()->attach($defaultTeam);
        $user->current_team_id = $defaultTeam->id;
        $user->save();
    }
}