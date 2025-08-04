<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define Gate for super_admin role
        Gate::define('super_admin', function ($user) {
            return $user->hasRole('super_admin');
        });

        // Define other role-based gates
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        Gate::define('finance_manager', function ($user) {
            return $user->hasRole('finance_manager') || $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        Gate::define('accountant', function ($user) {
            return $user->hasRole('accountant') || $user->hasRole('finance_manager') || $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        Gate::define('cashier', function ($user) {
            return $user->hasRole('cashier') || $user->hasRole('accountant') || $user->hasRole('finance_manager') || $user->hasRole('admin') || $user->hasRole('super_admin');
        });
    }
}
