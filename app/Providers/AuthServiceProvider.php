<?php

namespace App\Providers;

use App\Models\Transmittal;
use App\Policies\TransmittalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transmittal::class => TransmittalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // make gate for admin
        Gate::define('admin', function ($user) {
            return $user->level == 'administrator';
        });

        // make gate for superuser
        Gate::define('superuser', function ($user) {
            return $user->level == 'superuser';
        });

        // make gate for user
        Gate::define('user', function ($user) {
            return $user->level == 'user';
        });
    }
}
