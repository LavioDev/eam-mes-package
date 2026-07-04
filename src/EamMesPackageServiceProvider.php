<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools;

use Spatie\LaravelPackageTools\Commands\EamMesPublishCommand;
use Spatie\LaravelPackageTools\Commands\SyncExtensionsCommand;
use Spatie\LaravelPackageTools\Extensions\ExtensionValidator;
use Spatie\LaravelPackageTools\Migration\MigrationFileChecker;
use Spatie\LaravelPackageTools\Migration\MigrationGenerator;
use Spatie\LaravelPackageTools\Migration\StubRenderer;

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
                SyncExtensionsCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        // Bind migration services into the container so SyncExtensionsCommand
        // can receive them via constructor injection.
        $this->app->singleton(StubRenderer::class);
        $this->app->singleton(MigrationFileChecker::class);
        $this->app->singleton(ExtensionValidator::class);

        $this->app->singleton(MigrationGenerator::class, function ($app) {
            return new MigrationGenerator($app->make(StubRenderer::class));
        });
    }
}
