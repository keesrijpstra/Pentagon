<?php

namespace App\Providers;

use App\Models\Password;
use App\Filament\Pages\Passwords;
use App\Policies\CustomPagePolicy;
use App\Observers\PasswordObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Map your page class to the policy
        Passwords::class => CustomPagePolicy::class,
    ];
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
        Model::unguard();
// In AppServiceProvider or AuthServiceProvider
    Gate::policy(Passwords::class, CustomPagePolicy::class);
        Password::observe(PasswordObserver::class);
    }
}
