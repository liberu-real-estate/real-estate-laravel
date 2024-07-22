<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class RouteDebugServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $routes = Route::getRoutes();
            foreach ($routes as $route) {
                Log::info('Route: ' . $route->uri() . ' | Action: ' . json_encode($route->getAction()));
            }
        });
    }

    public function register()
    {
        //
    }
}