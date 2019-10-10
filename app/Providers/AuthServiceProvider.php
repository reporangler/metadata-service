<?php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('package-group-create',    'App\Policies\PackageGroupPolicy@create');
        Gate::define('package-group-update',    'App\Policies\PackageGroupPolicy@update');
        Gate::define('package-group-remove',    'App\Policies\PackageGroupPolicy@remove');
    }
}

