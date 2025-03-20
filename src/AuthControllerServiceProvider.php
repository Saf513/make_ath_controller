<?php

namespace Safia\Authcontroller;

use Illuminate\Support\ServiceProvider;
use Safia\Authcontroller\Console\Commands\MakeAuthControllerCommand;

class AuthControllerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAuthControllerCommand::class,
            ]);
            
            $this->publishes([
                __DIR__ . '/config/auth-controller.php' => config_path('auth-controller.php'),
            ], 'auth-controller-config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/auth-controller.php', 'auth-controller'
        );
    }
}