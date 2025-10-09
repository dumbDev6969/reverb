<?php

namespace App\Providers;
use App\Models\Report;
use App\Policies\ReportPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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

        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return 'App\\Policies\\'.class_basename($modelClass).'Policy';
        });
    }
}
