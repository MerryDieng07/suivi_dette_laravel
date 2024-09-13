<?php

namespace App\Providers;

use App\Models\Dette;
use App\Models\Client;
use App\Services\PdfService;
use App\Services\QrCodeService;
use App\Observers\DetteObserver;
use App\Observers\ClientObserver;
use Illuminate\Support\ServiceProvider;
use App\Services\UploadServiceInterface;
use App\Services\UploadServiceCloudinary;
use App\Services\PassportAuthentificationService;
use App\Services\AuthentificationServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Liaison correcte entre l'interface et l'implémentation
        $this->app->bind(AuthentificationServiceInterface::class, PassportAuthentificationService::class);

        // Autres liaisons et services
        $this->app->singleton('PdfFacade', function ($app) {
            return new PdfService();
        });
        $this->app->singleton('QrcodeFacade', function ($app) {
            return new QrCodeService();
        });
        $this->app->singleton('uploadService', function () {
            return $this->app->make(UploadServiceInterface::class);
        });

        $this->app->bind(UploadServiceInterface::class, UploadServiceCloudinary::class);

        

    
    }

    // protected $policies = [
    //     \App\Models\User::class => \App\Policies\UserPolicy::class,
    // ];
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /// Enregistrer l'observer
        Client::observe(ClientObserver::class);
        
        Dette::observe(DetteObserver::class);
    
    }

    
}
