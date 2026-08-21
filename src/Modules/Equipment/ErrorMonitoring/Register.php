<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring;

use Spatie\LaravelPackageTools\Modules\AbstractModuleProvider;

final class Register extends AbstractModuleProvider
{
    public function getDomain(): string
    {
        return 'Equipment';
    }

    public function getName(): string
    {
        return 'ErrorMonitoring';
    }

    public function getMigrations(): array
    {
        return [
            '2025_08_06_105535_eamo_create_equipment_error_logs_table.php',
        ];
    }
}