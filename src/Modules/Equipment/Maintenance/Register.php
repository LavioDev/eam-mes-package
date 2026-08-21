<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance;

use Spatie\LaravelPackageTools\Modules\AbstractModuleProvider;

final class Register extends AbstractModuleProvider
{
    public function getDomain(): string
    {
        return 'Equipment';
    }

    public function getName(): string
    {
        return 'Maintenance';
    }

    public function getMigrations(): array
    {
        return [
            '2025_08_06_161000_eamo_create_maintenance_categories_table.php',
            '2025_08_06_161100_eamo_create_maintenance_items_table.php',
            '2025_08_06_161200_eamo_create_maintenance_plans_table.php',
            '2025_08_06_161300_eamo_create_maintenance_schedules_table.php',
            '2025_08_06_161350_eamo_create_maintenance_schedule_user_table.php',
            '2025_08_06_161400_eamo_create_maintenance_logs_table.php',
        ];
    }
}