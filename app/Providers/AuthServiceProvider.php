<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * La carte des policies pour les modèles.
     */
    // protected $policies = [
    //     User::class => UserPolicy::class, 
    // ];

    /**
     * Enregistre n'importe quel service d'authentification ou d'autorisation.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
