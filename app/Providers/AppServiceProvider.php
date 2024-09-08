<?php

namespace App\Providers;

use App\Services\QrCodeService;
use Illuminate\Support\ServiceProvider;
use App\Services\AuthentificationServiceInterface;
use App\Services\PassportAuthentificationService;
use App\Services\PdfService;
use App\Services\SanctumAuthentificationService;
use App\Services\UploadService;
use App\Services\UploadServiceImpl;
use App\Services\UploadServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // $this->app->bind(UploadServiceInterface::class, UploadServiceImpl::class);
        $this->app->singleton('PdfFacade', function ($app) {
            return new PdfService();
        });
        $this->app->singleton('QrcodeFacade', function ($app) {
            return new QrCodeService();
        });
        $this->app->singleton('uploadService', function () {
            return $this->app->make(UploadServiceInterface::class);
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
