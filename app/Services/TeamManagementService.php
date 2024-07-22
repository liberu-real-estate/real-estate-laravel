<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Office; // Assuming you have an Office model

class TeamManagementService
{
    public function getOrCreateOfficeTeam(User $user): Team
    {
        $office = $user->office; // Assuming users have an office relationship

        if (!$office) {
            throw new \Exception("User does not have an associated office");
        }

        return Team::firstOrCreate(
            ['office_id' => $office->id],
            ['name' => $office->name . ' Team']
        );
    }

    public function assignUserToOfficeTeam(User $user): void
    {
        $team = $this->getOrCreateOfficeTeam($user);
        $user->teams()->syncWithoutDetaching([$team->id]);
        $user->switchTeam($team);
    }
}