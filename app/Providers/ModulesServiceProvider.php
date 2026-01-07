<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Aqui poderíamos carregar configs específicas de módulos
    }

    public function boot(): void
    {
        $modulesPath = base_path('modules');

        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);

            foreach ($modules as $module) {
                $moduleName = basename($module);
                
                // 1. Carregar Rotas (se existir arquivo routes.php ou api.php dentro de Infrastructure)
                $routesPath = $module . '/Infrastructure/routes.php';
                if (File::exists($routesPath)) {
                    $this->loadRoutesFrom($routesPath);
                }

                // 2. Carregar Migrations
                $migrationsPath = $module . '/Infrastructure/Database/Migrations';
                if (File::exists($migrationsPath)) {
                    $this->loadMigrationsFrom($migrationsPath);
                }

                // 3. Carregar Views (opcional, se usar Blade)
                $viewsPath = $module . '/Infrastructure/Resources/Views';
                if (File::exists($viewsPath)) {
                    $this->loadViewsFrom($viewsPath, strtolower($moduleName));
                }
            }
        }
    }
}