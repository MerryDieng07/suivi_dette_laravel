<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use App\Services\PassportAuthentificationService;
use App\Services\SanctumAuthentificationService;
use App\Services\AuthentificationServiceInterface;

class AuthCustomProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Configure the default authentication driver
        Auth::provider('custom', function ($app, $config) {
            return new PassportAuthentificationService(app('Laravel\Passport\TokenRepository'));
        });
        
        // Bind the AuthentificationServiceInterface to the Passport implementation by default
        $this->app->singleton(AuthentificationServiceInterface::class, function ($app) {
            return new PassportAuthentificationService(app('Laravel\Passport\TokenRepository'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
       
    }
}
