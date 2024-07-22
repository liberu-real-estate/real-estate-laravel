public function boot()
{
    /**
     * Disable Fortify routes.
     */
    Fortify::$registersRoutes = false;

    /**
     * Disable Jetstream routes.
     */
    Jetstream::$registersRoutes = false;

    /**
     * Remove CreatePersonalTeam listener
     */
    // Event::listen(
    //     Registered::class,
    //     CreatePersonalTeam::class,
    // );

    /**
     * Add listener to assign user to office team
     */
    Event::listen(Registered::class, function ($event) {
        $teamManagementService = app(TeamManagementService::class);
        $teamManagementService->assignUserToOfficeTeam($event->user);
    });
}