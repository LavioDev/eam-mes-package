<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment;

use Spatie\LaravelPackageTools\Modules\AbstractModuleProvider;

final class Register extends AbstractModuleProvider
{
    public function getDomain(): string
    {
        return 'Masterdata';
    }

    public function getName(): string
    {
        return 'Equipment';
    }

    public function getMigrations(): array
    {
        return [
            '2025_06_22_080000_eamo_create_eamo_equipment_categories_table.php',
            '2025_06_23_084823_eamo_create_eamo_equipment_table.php',
            '2025_06_23_084824_eamo_create_eamo_equipment_states_table.php',
            '2025_06_23_084825_eamo_create_eamo_equipment_images_table.php',
            '2025_07_03_095341_eamo_create_eamo_equipment_parameters_table.php',
            '2025_07_03_120000_eamo_create_eamo_equipment_errors_table.php',
        ];
    }
}
