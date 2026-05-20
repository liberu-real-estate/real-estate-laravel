<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Services\DocuSignService;
use App\Services\DigitalSignatureService;

class DigitalSignatureServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DocuSignService::class, function ($app) {
            $config = Config::get('services.docusign');
            return new DocuSignService($config);
        });

        $this->app->singleton(DigitalSignatureService::class, function ($app) {
            $docuSignService = $app->make(DocuSignService::class);
            return new DigitalSignatureService($docuSignService);
        });
    }
}
