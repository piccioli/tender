<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Nova\Events\StartedImpersonating;
use Laravel\Nova\Events\StoppedImpersonating;
use Illuminate\Support\Facades\Gate;
use App\Models\Tender;
use App\Policies\TenderPolicy;

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
        Event::listen(StartedImpersonating::class, function ($event) {
            logger("User {$event->impersonator->name} started impersonating {$event->impersonated->name}");
        });

        Event::listen(StoppedImpersonating::class, function ($event) {
            logger("User {$event->impersonator->name} stopped impersonating {$event->impersonated->name}");
        });

        Gate::policy(Tender::class, TenderPolicy::class);
    }
}
