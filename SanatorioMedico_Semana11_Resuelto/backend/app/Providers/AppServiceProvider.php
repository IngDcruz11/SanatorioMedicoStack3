<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
        \Illuminate\Support\Facades\Gate::define('pacientes.manage', function ($user): bool {
            return in_array('pacientes.manage', $user->permissions ?? [], true)
                && $user->tokenCan('pacientes.manage');
        });
        \Illuminate\Support\Facades\Gate::define('citas.manage', function ($user): bool {
            return in_array('citas.manage', $user->permissions ?? [], true)
                && $user->tokenCan('citas.manage');
        });
    }
}
