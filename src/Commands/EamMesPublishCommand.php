<?php

namespace Spatie\LaravelPackageTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EamMesPublishCommand extends Command
{
    protected $signature = 'eam-mes:publish 
                            {--all : Publish all submodules}
                            {--submodule= : Publish a specific submodule (checklist, error-monitoring, maintenance, parameter-log)}';

    protected $description = 'Publish code files (models, actions, requests, routes) and migrations for EAM MES submodules to the main application';

    protected array $submodules = [
        'checklist' => [
            'name' => 'Checklist',
            'migrations' => [
                '2025_08_05_113903_eamo_create_checklist_details_table.php',
                '2025_08_05_113908_eamo_create_checklist_sessions_table.php',
                '2025_11_11_134736_eamo_create_operating_times_table.php',
            ],
            'source_dir' => 'Checklist',
        ],
        'error-monitoring' => [
            'name' => 'ErrorMonitoring',
            'migrations' => [
                '2025_08_06_105535_eamo_create_equipment_error_logs_table.php',
            ],
            'source_dir' => 'ErrorMonitoring',
        ],
        'maintenance' => [
            'name' => 'Maintenance',
            'migrations' => [
                '2025_08_06_161253_eamo_create_maintenance_plans_table.php',
                '2025_08_06_161336_eamo_create_maintenance_schedules_table.php',
                '2025_08_06_162739_eamo_create_maintenance_items_table.php',
                '2025_08_06_162801_eamo_create_maintenance_categories_table.php',
                '2025_08_07_085425_eamo_create_maintenance_logs_table.php',
            ],
            'source_dir' => 'Maintenance',
        ],
        'parameter-log' => [
            'name' => 'ParameterLog',
            'migrations' => [
                '2025_08_06_102920_eamo_create_equipment_parameter_logs_table.php',
            ],
            'source_dir' => 'ParameterLog',
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

        // 1. Copy php files to modules/Equipment/<SubmoduleName>
        $sourcePath = __DIR__ . '/../' . $config['source_dir'];
        $destPath = base_path('modules/Equipment/' . $config['name']);

        if (File::exists($sourcePath)) {
            File::ensureDirectoryExists(dirname($destPath));
            File::copyDirectory($sourcePath, $destPath);
            $this->line(" - Copied code files to [modules/Equipment/{$config['name']}]");
        } else {
            $this->warn(" - Source code directory not found at {$sourcePath}");
        }

        // 2. Copy migrations to database/migrations
        $migrationsSourcePath = __DIR__ . '/../../database/migrations';
        $migrationsDestPath = database_path('migrations');

        foreach ($config['migrations'] as $migrationFile) {
            $srcFile = $migrationsSourcePath . '/' . $migrationFile;
            if (File::exists($srcFile)) {
                $cleanName = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationFile);
                $destFile = $migrationsDestPath . '/' . date('Y_m_d_His') . '_' . $cleanName;

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
