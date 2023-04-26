<?php

namespace App\Providers;

use App\Models\Delivery;
use App\Models\Transmittal;
use App\Policies\DeliveryPolicy;
use App\Policies\TransmittalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transmittal::class => TransmittalPolicy::class,
        Delivery::class => DeliveryPolicy::class,
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
        Gate::define('administrator', function ($user) {
            return $user->role == 'administrator';
        });

        // make gate for gateway
        Gate::define('gateway', function ($user) {
            return $user->role == 'gateway';
        });

        // make gate for courier
        Gate::define('courier', function ($user) {
            return $user->role == 'courier';
        });

        // make gate for user
        Gate::define('user', function ($user) {
            return $user->role == 'user';
        });
    }
}
