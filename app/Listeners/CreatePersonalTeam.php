<?php

namespace App\Listeners;

use App\Models\Team;
use Illuminate\Auth\Events\Registered;

class CreatePersonalTeam
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (!$user->belongsToTeam()) {
            $defaultTeam = Team::first();
            if ($defaultTeam instanceof Team) {
                $user->teams()->attach($defaultTeam);
                $user->switchTeam($defaultTeam);
            } else {
                \Log::error("Failed to retrieve a valid default team");
            }
        }
    }
}
