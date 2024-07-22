<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Branch;

class TeamManagementService
{
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