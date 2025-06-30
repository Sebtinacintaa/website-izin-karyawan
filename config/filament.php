<?php

return [

    'default_panel' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Panel Providers
    |--------------------------------------------------------------------------
    |
    | Panel service providers that register Filament panels.
    |
    */

    'panel-providers' => [
        App\Providers\Filament\AdminPanelProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Dark Mode
    |--------------------------------------------------------------------------
    */

    'dark_mode' => false,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => [
        'auth' => [
            \Filament\Http\Middleware\Authenticate::class,
        ],
        'base' => [
            \Filament\Http\Middleware\DisableBladeIconComponents::class,
            \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            \Filament\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Features
    |--------------------------------------------------------------------------
    */

    'spatie-tags' => [
        'enabled' => false,
    ],

    'cache' => [
        'enabled' => false,
    ],
];
