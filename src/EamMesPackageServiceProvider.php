<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools;

class EamMesPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('eam-mes-package')
            ->discoversMigrations()
            ->hasCommand(\Spatie\LaravelPackageTools\Commands\EamMesPublishCommand::class);
    }
}
