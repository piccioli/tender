<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateNovaPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:create-nova-page {name : The name of the page in kebab-case} {--fix : Fix existing corrupted ToolServiceProvider files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Nova page with all necessary configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pageName = $this->argument('name');
        
        // If --fix option is used, just fix existing files
        if ($this->option('fix')) {
            $className = Str::studly($pageName);
            if (File::exists("nova-components/$className")) {
                $this->fixCorruptedToolServiceProvider($className);
                $this->info("✅ Fixed ToolServiceProvider for '$className'");
                return 0;
            } else {
                $this->error("Component '$className' not found!");
                return 1;
            }
        }
        
        // Validate kebab-case format
        if (!preg_match('/^[a-z][a-z0-9-]*[a-z0-9]$/', $pageName)) {
            $this->error('The page name must be in kebab-case format (e.g., test-page, my-custom-page)');
            return 1;
        }

        // Convert to PascalCase for class name
        $className = Str::studly($pageName);
        
        // Check if component already exists
        if (File::exists("nova-components/$className")) {
            $this->error("The Nova component '$className' already exists!");
            return 1;
        }

        $this->info("Creating Nova page: $pageName");

        // Step 1: Create the Nova tool
        $this->step("1. Creating Nova tool...");
        $this->call('nova:tool', ['name' => "tender/$pageName"]);

        // Step 2: Register tool in NovaServiceProvider
        $this->step("2. Registering tool in NovaServiceProvider...");
        $this->registerToolInNovaServiceProvider($className);

        // Step 3: Configure routes in ToolServiceProvider
        $this->step("3. Configuring routes in ToolServiceProvider...");
        $this->configureToolServiceProvider($className);
        
        // Fix any corrupted files
        $this->fixCorruptedToolServiceProvider($className);

        // Step 4: Define Inertia route
        $this->step("4. Creating Inertia route...");
        $this->createInertiaRoute($className, $pageName);

        // Step 5: Create Vue page content
        $this->step("5. Creating Vue page content...");
        $this->createVuePage($className, $pageName);

        // Step 6: Compile assets
        $this->step("6. Compiling assets...");
        $this->compileAssets($className);

        // Step 7: Clear route cache
        $this->step("7. Clearing route cache...");
        $this->call('route:clear');

        $this->info("✅ Nova page '$pageName' created successfully!");
        $this->info("🌐 Page accessible at: /nova/$pageName");
        $this->info("📁 Component created in: nova-components/$className");

        $this->newLine();
        $this->info("📋 Summary of changes:");
        $this->line("   - Nova tool created: tender/$pageName");
        $this->line("   - Registered in: app/Providers/NovaServiceProvider.php");
        $this->line("   - Route configured: /nova/$pageName");
        $this->line("   - Vue page created: nova-components/$className/resources/js/pages/Tool.vue");
        $this->line("   - Assets compiled in: nova-components/$className/dist/");
        $this->newLine();
        $this->info("💡 To customize the page, edit: nova-components/$className/resources/js/pages/Tool.vue");

        return 0;
    }

    private function step($message)
    {
        $this->line("<fg=blue>[STEP]</> $message");
    }

    private function registerToolInNovaServiceProvider($className)
    {
        $filePath = app_path('Providers/NovaServiceProvider.php');
        $content = File::get($filePath);

        // Add import
        $importLine = "use Tender\\$className\\$className;";
        if (!str_contains($content, $importLine)) {
            $content = preg_replace(
                '/(use Tender\\\WelcomePage\\\WelcomePage;)/',
                "$1\nuse Tender\\$className\\$className;",
                $content
            );
        }

        // Add tool to array
        $toolLine = "            new $className(),";
        if (!str_contains($content, $toolLine)) {
            $content = preg_replace(
                '/(new WelcomePage,)/',
                "$1\n            $toolLine",
                $content
            );
        }

        File::put($filePath, $content);
    }

    private function configureToolServiceProvider($className)
    {
        $filePath = "nova-components/$className/src/ToolServiceProvider.php";
        
        $newRoutesMethod = <<<PHP
    /**
     * Register the tool's routes.
     */
    protected function routes(): void
    {
        if (\$this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', 'nova.auth', Authorize::class], '')
            ->group(__DIR__.'/../routes/inertia.php');
    }
}
PHP;

        $content = File::get($filePath);
        
        // Replace the entire routes method including comment, and ensure class closing
        $content = preg_replace(
            '/\s*\/\*\*\s*\n\s*\* Register the tool\'s routes\.\s*\n\s*\*\/\s*\n\s*protected function routes\(\): void\s*\{.*?\n\s*\}\s*\n\s*\}\s*$/s',
            $newRoutesMethod,
            $content
        );
        
        // If the replacement didn't work (no match), try a simpler approach
        if (!str_contains($content, 'Nova::router([')) {
            $content = preg_replace(
                '/protected function routes\(\): void\s*\{.*?\n\s*\}/s',
                'protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router([\'nova\', \'nova.auth\', Authorize::class], \'\')
            ->group(__DIR__.\'/../routes/inertia.php\');
    }',
                $content
            );
            
            // Ensure class closing brace
            if (!str_ends_with(trim($content), '}')) {
                $content .= "\n}\n";
            }
        }

        File::put($filePath, $content);
    }

    private function createInertiaRoute($className, $pageName)
    {
        $filePath = "nova-components/$className/routes/inertia.php";
        
        $content = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/$pageName', function () {
    return Inertia::render('$className');
});
PHP;

        File::put($filePath, $content);
    }

    private function createVuePage($className, $pageName)
    {
        $filePath = "nova-components/$className/resources/js/pages/Tool.vue";
        
        $content = <<<VUE
<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold">$pageName</h1>
    <p>Benvenuto nella tua pagina personalizzata di Nova.</p>
  </div>
</template>

<script>
export default {
  name: 'Tool',
}
</script>
VUE;

        File::put($filePath, $content);
    }

    private function compileAssets($className)
    {
        $componentPath = "nova-components/$className";
        
        // Install dependencies
        $this->info("Installing npm dependencies...");
        $this->executeCommand("cd $componentPath && npm install");
        
        // Compile assets
        $this->info("Compiling assets...");
        $this->executeCommand("cd $componentPath && npm run dev");
    }

    private function executeCommand($command)
    {
        $output = [];
        $returnCode = 0;
        
        exec($command . ' 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->warn("Command output: " . implode("\n", $output));
        }
        
        return $returnCode === 0;
    }
    
    private function fixCorruptedToolServiceProvider($className)
    {
        $filePath = "nova-components/$className/src/ToolServiceProvider.php";
        
        if (!File::exists($filePath)) {
            return;
        }
        
        $content = File::get($filePath);
        
        // Remove duplicate comment blocks
        $content = preg_replace(
            '/\/\*\*\s*\n\s*\* Register the tool\'s routes\.\s*\n\s*\*\/\s*\n\s*\/\*\*\s*\n\s*\* Register the tool\'s routes\.\s*\n\s*\*\/\s*\n/s',
            "/**\n     * Register the tool's routes.\n     */\n",
            $content
        );
        
        // Ensure proper class structure
        $correctContent = <<<PHP
<?php

namespace Tender\\$className;

use Illuminate\\Support\\Facades\\Route;
use Illuminate\\Support\\ServiceProvider;
use Laravel\\Nova\\Events\\ServingNova;
use Laravel\\Nova\\Nova;
use Tender\\$className\\Http\\Middleware\\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \$this->app->booted(function () {
            \$this->routes();
        });

        Nova::serving(function (ServingNova \$event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     */
    protected function routes(): void
    {
        if (\$this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', 'nova.auth', Authorize::class], '')
            ->group(__DIR__.'/../routes/inertia.php');
    }
}
PHP;

        // If the file is corrupted, replace it entirely
        if (substr_count($content, 'Register the tool\'s routes') > 1 || 
            !str_contains($content, 'Nova::router([') ||
            !str_ends_with(trim($content), '}')) {
            
            File::put($filePath, $correctContent);
            $this->warn("Fixed corrupted ToolServiceProvider.php file");
        }
    }
} 