<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance;

use App\Providers\IModuleProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Equipment\Maintenance\Infrastructure\Seeders\MaintenanceCategorySeeder;
use Modules\Equipment\Maintenance\Infrastructure\Seeders\MaintenanceScheduleSeeder;
use Modules\Equipment\Maintenance\Infrastructure\Seeders\MaintenanceItemSeeder;
use Modules\Equipment\Maintenance\Infrastructure\Seeders\MaintenancePlanSeeder;
use Modules\Equipment\Maintenance\Infrastructure\Seeders\MaintenanceLogSeeder;

final class Register extends ServiceProvider implements IModuleProvider
{
    public function seed(): void
    {
        app(MaintenanceCategorySeeder::class)->run();
        app(MaintenanceItemSeeder::class)->run();
        app(MaintenancePlanSeeder::class)->run();
        app(MaintenanceScheduleSeeder::class)->run();
        app(MaintenanceLogSeeder::class)->run();
    }

    public function getRoutePath(): string
    {
        return __DIR__ . '/Presentation/routes.php';
    }

    public function getMigrationPath(): string
    {
        return __DIR__ . '/Infrastructure/Migrations';
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