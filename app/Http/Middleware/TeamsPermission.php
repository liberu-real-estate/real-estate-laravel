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

        if ($user) {
            $teamManagementService = app(TeamManagementService::class);
            try {
                $team = $teamManagementService->getOrCreateOfficeTeam($user);
                $user->current_team_id = $team->id;
                $user->save();
                app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);
                \Log::info("Set permission team ID to: " . $team->id);
            } catch (\Exception $e) {
                \Log::error("Failed to set team for user: " . $user->id . ". Error: " . $e->getMessage());
            }
        } else {
            \Log::warning("No authenticated user");
        }

        return $next($request);
    }
}
