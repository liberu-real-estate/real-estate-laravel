<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class TeamManagementService
{
    public function createDefaultTeamForUser(User $user): Team
    {
        try {
            $this->ensureTablesExist();
            $defaultBranch = Branch::firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('No default branch found. Please set up at least one branch.');
        } catch (QueryException $e) {
            if ($e->getCode() == '42S02') {
                throw new \Exception('Database setup incomplete. Please run migrations.');
            }
            throw $e;
        }

        return $user->ownedTeams()->create([
            'name' => $defaultBranch->name . ' Team',
            'personal_team' => false,
            'branch_id' => $defaultBranch->id,
        ]);
    }

    public function createPersonalTeamForUser(User $user): Team
    {
        $this->ensureTablesExist();
        return $user->ownedTeams()->create([
            'name' => $user->name . "'s Team",
            'personal_team' => true,
        ]);
    }

    public function assignUserToDefaultTeam(User $user): void
    {
        $this->ensureTablesExist();
        $defaultTeam = Team::where('personal_team', false)->first();

        if (!$defaultTeam) {
            $defaultTeam = $this->createDefaultTeamForUser($user);
        }

        $this->assignUserToTeam($user, $defaultTeam);
    }

    public function assignUserToTeam(User $user, Team $team): void
    {
        $this->ensureTablesExist();
        if (!$user->belongsToTeam($team)) {
            $user->teams()->attach($team, ['role' => 'member']);
        }
        $user->switchTeam($team);
    }

    public function switchTeam(User $user, Team $team): void
    {
        $this->ensureTablesExist();
        if (!$user->belongsToTeam($team)) {
            throw new \Exception('User does not belong to the specified team.');
        }
        $user->switchTeam($team);
    }

    private function ensureTablesExist(): void
    {
        $requiredTables = ['teams', 'team_user', 'team_invitations', 'branches'];
        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                throw new \Exception("Table '{$table}' does not exist. Please run migrations.");
            }
        }
    }
}