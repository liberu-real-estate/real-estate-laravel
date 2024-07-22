    protected function assignOrCreateTeam(User $user): Team
    {
        try {
            $defaultTeam = Team::where('name', 'default')->first();

            if (!$defaultTeam) {
                throw new Exception('Default team not found. Please run the DefaultTeamSeeder.');
            }

            $user->teams()->attach($defaultTeam);
            $user->current_team_id = $defaultTeam->id;
            $user->save();

            return $defaultTeam;
        } catch (Exception $e) {
            Log::error('Failed to assign default team', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Failed to assign default team. Please try again later.');
        }
    }