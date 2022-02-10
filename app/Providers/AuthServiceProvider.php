<?php

namespace App\Providers;

// use Laravel\Passport\Passport;
// use Laravel\Passport\PassportServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('isBuyer', function ($user) {
            return $user->role == 3;
        });
        // Passport::routes();
    }
}
