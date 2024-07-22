    public function handle(Request $request, Closure $next)
    {
        if (!Filament::getTenant() && auth()->check()) {
            $user = auth()->user();
            $defaultTeam = $user->currentTeam ?? Team::where('name', 'default')->first();

            if (!$defaultTeam) {
                \Log::error("Default team not found. Please run the DefaultTeamSeeder.");
                return redirect()->route('home')->with('error', 'Unable to assign default team. Please contact support.');
            }

            if (!$user->belongsToTeam($defaultTeam)) {
                $user->teams()->attach($defaultTeam);
                $user->current_team_id = $defaultTeam->id;
                $user->save();
            }

            Filament::setTenant($defaultTeam);
        }
        return $next($request);
    }