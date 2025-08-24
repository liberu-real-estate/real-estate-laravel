<?php

namespace App\Providers;

use Illuminate\Mail\Events\MessageSent;
use App\Listeners\EmailTracker;
use App\Jobs\CheckPropertyAlerts;
use App\Jobs\CheckLeaseNotifications;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schedule;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MessageSent::class => [
            EmailTracker::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            Schedule::job(new CheckPropertyAlerts)->daily();
            Schedule::job(new CheckLeaseNotifications)->daily();
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
