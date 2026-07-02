<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist;

use App\Providers\IModuleProvider;
use Illuminate\Support\ServiceProvider;

final class Register extends ServiceProvider implements IModuleProvider
{
    public function seed(): void
    {
        // TODO: Add seeders here
        // $seeders = [
        //     // Add seeder classes here
        // ];
        // foreach ($seeders as $seeder) {
        //     app($seeder)->run();
        // }
    }

    public function getRoutePath(): string
    {
        return __DIR__ . '/routes.php';
    }

    public function getMigrationPath(): string
    {
        return __DIR__ . '/Migrations';
    }

    public function registerPolicies(): void
    {
        // TODO: Register policies here
        // Gate::policy(Model::class, ModelPolicy::class);
    }

    public function boot(): void
    {
        // TODO: Add boot logic here
    }
} 