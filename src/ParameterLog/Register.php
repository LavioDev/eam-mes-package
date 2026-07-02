<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog;

use App\Providers\IModuleProvider;
use Illuminate\Support\ServiceProvider;

final class Register extends ServiceProvider implements IModuleProvider
{
    public function seed(): void
    {
        // $seeders = [
        //     Infrastructure\Seeders\EquipmentParameterLogSeeder::class,
        // ];
        // foreach ($seeders as $seeder) {
        //     app($seeder)->run();
        // }
    }

    public function getRoutePath(): string
    {
        return __DIR__.'/Presentation/routes.php';
    }

    public function getMigrationPath(): string
    {
        return __DIR__.'/Infrastructure/Migrations';
    }

    public function registerPolicies(): void
    {
        \Illuminate\Support\Facades\Gate::policy(
            Infrastructure\Models\EquipmentParameterLog::class,
            Presentation\Policies\EquipmentParameterLogPolicy::class
        );
    }

    public function boot(): void
    {
        // TODO: Add boot logic here
    }
}
