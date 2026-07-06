<?php

namespace Spatie\LaravelPackageTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EamMesPublishCommand extends Command
{
    protected $signature = 'eam-mes:publish 
                            {--all : Publish all submodules (including core)}
                            {--submodule= : Publish a specific submodule (core, checklist, error-monitoring, maintenance, parameter-log, equipment, masterdata-equipment)}';

    protected $description = 'Publish code files (models, actions, requests, routes) and migrations for EAM MES submodules to the main application';

    protected array $submodules = [
        'core' => [
            'name' => 'Core',
            'migrations' => [
                '2026_07_05_000000_create_eam_extension_requests_table.php',
            ],
            'source_dir' => '',
        ],
        'checklist' => [
            'name' => 'Checklist',
            'migrations' => [
                '2025_08_05_113908_eamo_create_checklist_sessions_table.php',
                '2025_08_05_113910_eamo_create_checklist_details_table.php',
                '2025_11_11_134736_eamo_create_operating_times_table.php',
            ],
            'source_dir' => 'Equipment/Checklist',
            'dest_dir' => 'modules/Equipment/Checklist',
        ],
        'error-monitoring' => [
            'name' => 'ErrorMonitoring',
            'migrations' => [
                '2025_08_06_105535_eamo_create_equipment_error_logs_table.php',
            ],
            'source_dir' => 'Equipment/ErrorMonitoring',
            'dest_dir' => 'modules/Equipment/ErrorMonitoring',
        ],
        'maintenance' => [
            'name' => 'Maintenance',
            'migrations' => [
                '2025_08_06_161000_eamo_create_maintenance_categories_table.php',
                '2025_08_06_161100_eamo_create_maintenance_items_table.php',
                '2025_08_06_161200_eamo_create_maintenance_plans_table.php',
                '2025_08_06_161300_eamo_create_maintenance_schedules_table.php',
                '2025_08_06_161400_eamo_create_maintenance_logs_table.php',
            ],
            'source_dir' => 'Equipment/Maintenance',
            'dest_dir' => 'modules/Equipment/Maintenance',
        ],
        'parameter-log' => [
            'name' => 'ParameterLog',
            'migrations' => [
                '2025_08_06_102920_eamo_create_equipment_parameter_logs_table.php',
            ],
            'source_dir' => 'Equipment/ParameterLog',
            'dest_dir' => 'modules/Equipment/ParameterLog',
        ],
        'equipment' => [
            'name' => 'Equipment',
            'migrations' => [
                '2025_08_04_064327_eamo_create_iot_logs_table.php',
                '2025_08_04_100000_eamo_seed_short_stop_equipment_error_for_iot_equipment.php',
            ],
            'source_dir' => 'Equipment/MasterData',
            'dest_dir' => 'modules/Equipment/MasterData',
        ],
        'masterdata-equipment' => [
            'name' => 'MasterdataEquipment',
            'migrations' => [
                '2025_06_23_084823_eamo_create_equipment_table.php',
                '2025_07_03_095341_eamo_create_equipment_parameters_table.php',
                '2025_07_03_102525_eamo_create_standard_parameters_table.php',
                '2025_07_03_120000_eamo_create_equipment_errors_table.php',
                '2025_08_04_092812_eamo_create_equipment_equipment_errors_table.php',
            ],
            'source_dir' => 'Masterdata/Equipment',
            'dest_dir' => 'modules/Masterdata/Equipment',
        ],
    ];

    public function handle(): int
    {
        $all = $this->option('all');
        $submodule = $this->option('submodule');

        if (!$all && !$submodule) {
            $this->error('Please specify either --all or --submodule=<name>.');
            return 1;
        }

        if ($all) {
            foreach ($this->submodules as $key => $config) {
                $this->publishSubmodule($key);
            }
            return 0;
        }

        $submodule = strtolower($submodule);
        if (!array_key_exists($submodule, $this->submodules)) {
            $this->error("Submodule '{$submodule}' not found. Available submodules: " . implode(', ', array_keys($this->submodules)));
            return 1;
        }

        $this->publishSubmodule($submodule);
        return 0;
    }

    protected function publishSubmodule(string $key): void
    {
        $config = $this->submodules[$key];
        $this->info("Publishing submodule: {$config['name']}...");

        // 1. Copy php files if source_dir is present
        if (!empty($config['source_dir'])) {
            $sourcePath = __DIR__ . '/../Modules/' . $config['source_dir'];
            $destPath = base_path($config['dest_dir'] ?? ('modules/Equipment/' . $config['name']));

            if (File::exists($sourcePath)) {
                File::ensureDirectoryExists(dirname($destPath));
                File::copyDirectory($sourcePath, $destPath);
                $this->line(" - Copied code files to [{$config['dest_dir']}]");
            } else {
                $this->warn(" - Source code directory not found at {$sourcePath}");
            }
        }

        // 2. Copy migrations to database/migrations
        $migrationsSourcePath = __DIR__ . '/../../database/migrations';
        $migrationsDestPath = database_path('migrations');
        $baseTime = time();

        foreach ($config['migrations'] as $index => $migrationFile) {
            $srcFile = $migrationsSourcePath . '/' . $migrationFile;
            if (File::exists($srcFile)) {
                $cleanName = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationFile);
                // Increment time by index seconds to preserve chronological ordering
                $destFile = $migrationsDestPath . '/' . date('Y_m_d_His', $baseTime + $index) . '_' . $cleanName;

                // Avoid duplicate publication if it contains the clean name
                $exists = false;
                if (File::exists($migrationsDestPath)) {
                    foreach (File::files($migrationsDestPath) as $file) {
                        if (str_contains($file->getFilename(), $cleanName)) {
                            $exists = true;
                            break;
                        }
                    }
                }

                if (!$exists) {
                    File::ensureDirectoryExists($migrationsDestPath);
                    File::copy($srcFile, $destFile);
                    $this->line(" - Published migration [{$cleanName}] to database/migrations/");
                } else {
                    $this->line(" - Migration [{$cleanName}] already exists in database/migrations/");
                }
            }
        }

        $this->info("Submodule {$config['name']} published successfully.");
    }
}
