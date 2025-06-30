<?php

namespace Tender\MontFlow;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Tender\MontFlow\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }    /**
     * Register the tool's routes.
     */
    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', 'nova.auth', Authorize::class], '')
            ->group(__DIR__.'/../routes/inertia.php');
    }
}