<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools;

use Spatie\LaravelPackageTools\Commands\EamMesPublishCommand;
use Spatie\LaravelPackageTools\Commands\MakeModuleCommand;
use Spatie\LaravelPackageTools\Commands\SyncExtensionsCommand;
use Spatie\LaravelPackageTools\Extensions\ExtensionValidator;
use Spatie\LaravelPackageTools\Migration\MigrationFileChecker;
use Spatie\LaravelPackageTools\Migration\MigrationGenerator;
use Spatie\LaravelPackageTools\Migration\StubRenderer;
use Spatie\LaravelPackageTools\Modules\ModuleRegistry;

class EamMesPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('eam-mes-package')
            ->discoversMigrations()
            ->hasConfigFile('eam')
            ->hasCommands([
                EamMesPublishCommand::class,
                MakeModuleCommand::class,
                SyncExtensionsCommand::class,
            ]);
    }


    public function packageRegistered(): void
    {
        // Bind migration services into the container so SyncExtensionsCommand
        // and Controllers can receive them via constructor injection.
        $this->app->singleton(StubRenderer::class);
        $this->app->singleton(MigrationFileChecker::class);
        $this->app->singleton(ExtensionValidator::class);

        $this->app->singleton(MigrationGenerator::class, function ($app) {
            return new MigrationGenerator($app->make(StubRenderer::class));
        });

        $this->app->singleton(ModuleRegistry::class, function () {
            $registry = new ModuleRegistry();
            if (config('eam.modules.discovery', true)) {
                $registry->discover(__DIR__ . '/Modules');
            }

            return $registry;
        });
    }

    public function packageBooted(): void
    {
        // Automatically load route files defined inside the package
        $this->loadRoutesFrom(__DIR__ . '/../routes/eam-api.php');

        // Boot all enabled modules registered in ModuleRegistry
        $registry = $this->app->make(ModuleRegistry::class);
        foreach ($registry->enabled() as $module) {
            $module->register();
            $module->boot();
        }
    }
}

