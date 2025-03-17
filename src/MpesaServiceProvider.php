<?php

namespace Mpesa\Sdk;

use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish the config file to the Laravel project's config directory
        $this->publishes([
            __DIR__ . '/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'mpesa-config');
    }

    public function register()
    {
        // Merge the package config with the application's config
        $this->mergeConfigFrom(__DIR__ . '/../config/mpesa.php', 'mpesa');
    }
}