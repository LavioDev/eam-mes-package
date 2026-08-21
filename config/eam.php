<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Table Extensions
    |--------------------------------------------------------------------------
    |
    | Register classes that extend the schema of tables provided by the
    | EAM-MES package. Each class must implement:
    |
    |   Spatie\LaravelPackageTools\Contracts\TableExtension
    |
    | After adding or updating entries, run:
    |
    |   php artisan eam:sync-extensions
    |
    | to generate the corresponding migration files, then run:
    |
    |   php artisan migrate
    |
    */

    'extensions' => [
        // App\Extensions\MaintenancePlanExtension::class,
        // App\Extensions\ChecklistDetailExtension::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Migrate
    |--------------------------------------------------------------------------
    |
    | When set to true, `eam:sync-extensions` will automatically call
    | `php artisan migrate` after generating the migration files.
    |
    | Recommended: keep false in production, enable only in local/dev.
    |
    */

    'auto_migrate' => env('EAM_AUTO_MIGRATE', false),

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for HTTP table extension endpoints.
    |
    */

    'api' => [
        'prefix'     => 'eam/api',
        'middleware' => ['api'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    |
    | Background queue options for running asynchronous migrations via HTTP API.
    |
    */

    'queue' => [
        'connection' => env('EAM_QUEUE_CONNECTION', 'sync'),
        'name'       => env('EAM_QUEUE_NAME', 'eam-extensions'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Submodules Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for submodule auto-discovery and activation.
    |
    */

    'modules' => [
        'discovery' => env('EAM_MODULE_DISCOVERY', true),
        'disabled'  => [],
    ],

];

