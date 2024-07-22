<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use App\Models\Team;
use Illuminate\Http\Request;

class AssignDefaultTeam
{
    public function handle(Request $request, Closure $next)
    {
        if (!Filament::getTenant() && auth()->check()) {
            $defaultTeam = auth()->user()->currentTeam ?? auth()->user()->ownedTeams()->first();
            if ($defaultTeam instanceof Team) {
                Filament::setTenant($defaultTeam);
            } else {
                \Log::warning("Unable to set default team for user: " . auth()->id());
            }
        }
        return $next($request);
    }
}