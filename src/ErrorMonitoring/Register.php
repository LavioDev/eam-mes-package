<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring;

use App\Providers\IModuleProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Seeders\EquipmentErrorLogSeeder;

final class Register extends ServiceProvider implements IModuleProvider
{
    public function seed(): void
    {
        // app(EquipmentErrorLogSeeder::class)->run();
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