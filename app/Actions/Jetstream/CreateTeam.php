public function create(User $user, array $input): Team
{
    Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

    Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
    ])->validateWithBag('createTeam');

    AddingTeam::dispatch($user);

    $teamManagementService = app(TeamManagementService::class);
    $team = $teamManagementService->getOrCreateOfficeTeam($user);

    return $team;
}