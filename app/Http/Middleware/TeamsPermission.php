<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class TeamsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
    
        if ($user && $user->current_team_id) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
            \Log::info("Set permission team ID to: " . $user->current_team_id);
        } elseif ($user) {
            \Log::warning("No current team ID for user: " . $user->id);
    
            // Attempt to set a default team
            $defaultTeam = $user->ownedTeams()->first();
            if ($defaultTeam) {
                $teamId = $defaultTeam->id;
                if (is_string($teamId) || is_int($teamId)) {
                    $user->current_team_id = $teamId;
                    $user->save();
                    app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);
                    \Log::info("Set default team ID to: " . $teamId);
                } else {
                    \Log::error("Invalid team ID type: " . gettype($teamId));
                }
            } else {
                \Log::error("User has no teams: " . $user->id);
            }
        } else {
            \Log::warning("No authenticated user");
        }
    
        return $next($request);
    }
}
