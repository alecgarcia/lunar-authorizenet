<?php

namespace alecgarcia\LunarAuthorizeNet;

use Lunar\Facades\Payments;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use alecgarcia\LunarAuthorizeNet\Commands\LunarAuthorizeNetCommand;

class LunarAuthorizeNetServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        // Register our payment type.
        Payments::extend('authorizenet', function ($app) {
            return $app->make(AuthorizeNetPaymentType::class);
        });

        $this->app->singleton('alec:authorizenet', function ($app) {
            return $app->make(AuthorizeNet::class);
        });
    }
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lunar-authorizenet')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lunar_authorizenet_table')
            ->hasCommand(LunarAuthorizeNetCommand::class);
    }
}
