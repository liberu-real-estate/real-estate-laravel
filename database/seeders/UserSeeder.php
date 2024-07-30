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
        // Créer ou mettre à jour l'utilisateur admin
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'], // Conditions for finding the existing user
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');
        $this->createTeamForUser($adminUser);

        // Créer ou mettre à jour l'utilisateur staff
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@example.com'], // Conditions for finding the existing user
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $staffUser->assignRole('staff');
        $this->createTeamForUser($staffUser);

        // Créer des utilisateurs supplémentaires avec des équipes (commenté)
        // User::factory(8)->create()->each(function ($user) {
        //     $this->createTeamForUser($user);
        // });
    }

    private function createTeamForUser($user)
    {
        // Définir l'équipe actuelle pour l'utilisateur
        $user->current_team_id = 1;
        $user->save();
    }
}
