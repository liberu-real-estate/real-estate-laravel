<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \App\Http\Middleware\RoleBasedRedirect::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'role.redirect' => \App\Http\Middleware\RoleBasedRedirect::class,
            'teams.permission' => \App\Http\Middleware\TeamsPermission::class,
        ]);

        $middleware->throttleApi();
    })
    ->booted(function () {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('chatbot', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (\App\Exceptions\OnTheMarketApiException $e) {
            Log::error('OnTheMarket API Error: ' . $e->getMessage());
        });

        $exceptions->report(function (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error: ' . $e->getMessage());
        });
    })->create();
