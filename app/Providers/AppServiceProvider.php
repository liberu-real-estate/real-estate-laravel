<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\PropertyBooking;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SiteSettingsService::class, function ($app) {
            return new SiteSettingsService();
        });
    }

    public function boot()
    {
        Livewire::component('property-booking', PropertyBooking::class);
    }
}
</original_code>
</code_change>

This change explicitly registers the PropertyBooking component with Livewire.

After implementing these changes, the "missing livewire component property-booking" error should be resolved. The PropertyBooking component should now be properly recognized and loaded in the property detail view.

If you continue to experience issues, please check the Laravel and Livewire logs for any specific error messages that could provide more insight into the problem. Additionally, ensure that all files have the correct permissions and are readable by the web server.

Let me know if you need any further assistance or if you encounter any other issues!