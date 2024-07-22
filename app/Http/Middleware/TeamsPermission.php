<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->currentTeam) {
            // Redirect to a default route or show an error
            return redirect()->route('home')->with('error', 'You must be part of a team to access this area.');
        }

        // Check if the user has permission to access the current route
        // You can implement your team-based permission logic here

        return $next($request);
    }
}