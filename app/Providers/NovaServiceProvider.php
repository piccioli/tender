<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            $menu = [
                MenuSection::make('Home', [
                    MenuItem::dashboard(\App\Nova\Dashboards\Main::class),
                ])->icon('home')->collapsable(),
                
                MenuSection::make('Bandi', [
                    MenuItem::resource(\App\Nova\Tender::class)->name('Tutti'),
                ])->icon('document-text')->collapsable(),
            ];

            // Aggiungi la sezione Admin solo se l'utente ha il ruolo admin
            if ($request->user() && $request->user()->hasRole('admin')) {
                $menu[] = MenuSection::make('Admin', [
                    MenuItem::resource(\App\Nova\User::class),
                    MenuItem::resource(\App\Nova\Role::class),
                    MenuItem::resource(\App\Nova\Permission::class),
                ])->icon('shield-check')->collapsable();
            }

            return $menu;
        });
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function (User $user) {
            // Allow only users with admin role to access Nova
            return $user->hasRole('admin');
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();
    }
}
