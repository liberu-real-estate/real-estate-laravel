<?php

namespace App\Listeners;

use App\Services\TeamManagementService;
use Filament\Events\Auth\Registered;

class CreatePersonalTeam
{
    protected $teamManagementService;

    public function __construct(TeamManagementService $teamManagementService)
    {
        $this->teamManagementService = $teamManagementService;
    }

    public function handle(Registered $event): void
    {
        $this->teamManagementService->assignUserToDefaultTeam($event->user);
    }
}