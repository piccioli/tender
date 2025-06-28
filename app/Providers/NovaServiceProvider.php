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
use Tender\WelcomePage\WelcomePage;
use Illuminate\Support\Facades\Blade;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Get Nova version from composer.lock
     */
    private function getNovaVersion(): string
    {
        try {
            $composerLockPath = base_path('composer.lock');
            if (file_exists($composerLockPath)) {
                $composerLock = json_decode(file_get_contents($composerLockPath), true);
                $novaPackage = collect($composerLock['packages'])->firstWhere('name', 'laravel/nova');
                return $novaPackage['version'] ?? 'Unknown';
            }
        } catch (Exception $e) {
            // Log error if needed
            // \Log::warning('Could not read Nova version from composer.lock: ' . $e->getMessage());
        }
        
        return 'Unknown';
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            $menu = [
                MenuSection::make('Home', [
                    MenuItem::externalLink('Welcome','/nova/welcome-page'),
                    MenuItem::dashboard(\App\Nova\Dashboards\Main::class)->name('Nova'),
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

        Nova::footer(function (Request $request) {
            $novaVersion = $this->getNovaVersion();

            return Blade::render('
                <div class="text-center py-4 px-6 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center mb-2">
                        <img src="/img/logo-montagna-servizi.png" alt="Montagna Servizi" class="h-6 w-auto mr-3">
                        <span class="font-semibold text-lg text-gray-800 dark:text-gray-200">Montagna Servizi SCPA</span>
                    </div>
                    
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        © 2025 Montagna Servizi SCPA | Versione App: {{ config("app.version", "???") }}
                    </div>
                    
                    <div class="text-xs text-gray-500 dark:text-gray-500 flex flex-wrap justify-center gap-4">
                        <span>Laravel {{ app()->version() }}</span>
                        <span>PHP {{ phpversion() }}</span>
                        <span>Nova {{ $novaVersion }}</span>
                        <span>Ambiente: {{ config("app.env") }}</span>
                    </div>
                    
                    <div class="mt-3 text-xs">
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline mx-2">Privacy Policy</a>
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline mx-2">Termini di Servizio</a>
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline mx-4">Supporto</a>
                    </div>
                </div>
            ', ['novaVersion' => $novaVersion]);
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
        return [
            new WelcomePage,
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        Nova::initialPath('/welcome-page');
    }
}
