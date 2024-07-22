protected function createTeam(User $user): void
{
    $teamManagementService = app(TeamManagementService::class);
    $teamManagementService->assignUserToOfficeTeam($user);
}