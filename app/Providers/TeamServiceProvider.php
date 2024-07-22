<?php

namespace App\Providers;

use App\Models\Addr;
use App\Models\BatchData;
use App\Models\Chan;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\FamilySlgs;
use App\Models\Person;
use App\Models\PersonAlia;
use App\Models\PersonAsso;
use App\Models\PersonEvent;
use App\Models\Subm;
use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
