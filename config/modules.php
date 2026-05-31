<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Path
    |--------------------------------------------------------------------------
    |
    | This value determines the path where modules are stored.
    |
    */

    'path' => app_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Auto Discovery
    |--------------------------------------------------------------------------
    |
    | When enabled, the module system will automatically discover and register
    | modules found in the modules directory.
    |
    */

    'auto_discovery' => true,

    /*
    |--------------------------------------------------------------------------
    | Development Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, additional debugging information will be available
    | and modules will be reloaded on each request.
    |
    */

    'development' => env('MODULES_DEVELOPMENT', env('APP_DEBUG', false)),

    /*
    |--------------------------------------------------------------------------
    | Cache Modules
    |--------------------------------------------------------------------------
    |
    | When enabled, module information will be cached to improve performance.
    |
    */

    'cache' => env('MODULES_CACHE', true),

    'cache_key' => 'app.modules',

    'cache_ttl' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    */

    'namespace' => 'App\\Modules',

    /*
    |--------------------------------------------------------------------------
    | Enabled Modules
    |--------------------------------------------------------------------------
    |
    | Modules explicitly enabled regardless of database state.
    |
    */

    'enabled' => [
        // 'BlogModule',
    ],

    /*
    |--------------------------------------------------------------------------
    | External Module Paths
    |--------------------------------------------------------------------------
    |
    | Additional paths to scan for modules from vendor packages.
    |
    */

    'external_paths' => [
        // base_path('vendor/your-vendor/your-package/modules'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Load Composer Modules
    |--------------------------------------------------------------------------
    |
    | When enabled, automatically scan vendor packages for modules.
    |
    */

    'load_composer_modules' => env('MODULES_LOAD_COMPOSER', false),

    /*
    |--------------------------------------------------------------------------
    | Module Assets
    |--------------------------------------------------------------------------
    */

    'assets' => [
        'path' => public_path('modules'),
        'url' => '/modules',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Views
    |--------------------------------------------------------------------------
    */

    'views' => [
        'namespace_prefix' => 'module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Translations
    |--------------------------------------------------------------------------
    */

    'translations' => [
        'namespace_prefix' => 'module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Requirements
    |--------------------------------------------------------------------------
    |
    | Global requirements that modules must meet.
    |
    */

    'requirements' => [
        'php' => '8.5',
        'laravel' => '13.0',
    ],

];
