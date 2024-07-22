<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Office; // Assuming you have an Office model
use App\Models\Branch;

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

    public function createDefaultTeamForUser(User $user): Team
    {
        $defaultBranch = Branch::first();

        if (!$defaultBranch) {
            throw new \Exception('No default branch found. Please set up at least one branch.');
        }

        return $user->ownedTeams()->create([
            'name' => $defaultBranch->name . ' Team',
            'personal_team' => false,
            'branch_id' => $defaultBranch->id,
        ]);
    }

    public function assignUserToDefaultTeam(User $user): void
    {
        $defaultTeam = Team::where('personal_team', false)->first();

        if (!$defaultTeam) {
            $defaultTeam = $this->createDefaultTeamForUser($user);
        }

        $user->teams()->syncWithoutDetaching([$defaultTeam->id]);
        $user->switchTeam($defaultTeam);
    }
}