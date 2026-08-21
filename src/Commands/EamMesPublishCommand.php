<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Modules\AbstractModuleProvider;
use Spatie\LaravelPackageTools\Modules\ModuleRegistry;

class EamMesPublishCommand extends Command
{
    protected $signature = 'eam-mes:publish
                            {--all : Publish all registered modules and core migrations}
                            {--module= : Publish all submodules under a specific domain (e.g. equipment, masterdata-equipment)}
                            {--submodule= : Publish a specific submodule (e.g. checklist, error-monitoring, maintenance, parameter-log, management, equipment, masterdata-equipment)}';

    protected $description = 'Publish code files and migrations for EAM MES modules to the main application';

    public function __construct(
        private readonly ModuleRegistry $registry
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $all = $this->option('all');
        $module = $this->option('module');
        $submodule = $this->option('submodule');

        if (! $all && ! $module && ! $submodule) {
            $this->error('Please specify either --all, --module=<name>, or --submodule=<name>.');

            return self::FAILURE;
        }

        $modules = $this->registry->all();

        if ($all) {
            $this->publishCore();
            $this->publishManagement();
            foreach ($modules as $provider) {
                $this->publishModule($provider);
            }

            return self::SUCCESS;
        }

        if ($module) {
            $target = strtolower((string) $module);

            if ($target === 'equipment') {
                $matched = $modules->filter(fn (AbstractModuleProvider $p) => strtolower($p->getDomain()) === 'equipment');
                foreach ($matched as $provider) {
                    $this->publishModule($provider);
                }
                $this->publishManagement();

                return self::SUCCESS;
            }

            if ($target === 'masterdata-equipment' || $target === 'masterdata') {
                $matched = $modules->filter(fn (AbstractModuleProvider $p) => strtolower($p->getDomain()) === 'masterdata' || $p->getIdentifier() === 'masterdata.equipment');
                foreach ($matched as $provider) {
                    $this->publishModule($provider);
                }

                return self::SUCCESS;
            }

            $matched = $modules->filter(fn (AbstractModuleProvider $p) => strtolower($p->getDomain()) === $target);
            if ($matched->isEmpty()) {
                $this->error("No submodules found under domain '{$module}'.");

                return self::FAILURE;
            }

            foreach ($matched as $provider) {
                $this->publishModule($provider);
            }

            return self::SUCCESS;
        }

        if ($submodule) {
            $target = strtolower((string) $submodule);

            if ($target === 'core') {
                $this->publishCore();

                return self::SUCCESS;
            }

            // Legacy alias: 'equipment' or 'management'
            if ($target === 'equipment' || $target === 'management') {
                $this->publishManagement();

                return self::SUCCESS;
            }

            // Legacy alias: 'masterdata-equipment'
            if ($target === 'masterdata-equipment') {
                $provider = $modules->get('masterdata.equipment');
                if ($provider) {
                    $this->publishModule($provider);

                    return self::SUCCESS;
                }
            }

            $provider = $modules->first(function (AbstractModuleProvider $p) use ($target) {
                return strtolower($p->getName()) === $target
                    || $p->getIdentifier() === $target
                    || str_replace('.', '-', $p->getIdentifier()) === $target
                    || strtolower(str_replace(' ', '', $p->getName())) === str_replace('-', '', $target);
            });

            if (! $provider) {
                $this->error("Submodule '{$submodule}' not found.");

                return self::FAILURE;
            }

            $this->publishModule($provider);

            return self::SUCCESS;
        }

        return self::SUCCESS;
    }

    protected function publishCore(): void
    {
        $this->info('Publishing Core migrations...');
        $coreMigrations = [
            '2026_07_05_000000_create_eamo_extension_requests_table.php',
        ];

        $this->copyMigrations($coreMigrations);
    }

    protected function publishManagement(): void
    {
        $this->info('Publishing Management migrations...');
        $managementMigrations = [
            '2025_08_04_100000_eamo_seed_short_stop_equipment_error_for_iot_equipment.php',
        ];

        $this->copyMigrations($managementMigrations);
    }

    protected function publishModule(AbstractModuleProvider $provider): void
    {
        $this->info("Publishing submodule: [{$provider->getDomain()}/{$provider->getName()}]...");

        $sourcePath = $provider->getModulePath();
        $destPath = base_path("modules/{$provider->getDomain()}/{$provider->getName()}");

        if (File::exists($sourcePath)) {
            File::ensureDirectoryExists(dirname($destPath));
            File::copyDirectory($sourcePath, $destPath);
            $this->line(" - Copied code files to [modules/{$provider->getDomain()}/{$provider->getName()}]");
        } else {
            $this->warn(" - Source code directory not found at {$sourcePath}");
        }

        $this->copyMigrations($provider->getMigrations());

        $this->info("Submodule [{$provider->getDomain()}/{$provider->getName()}] published successfully.");
    }

    protected function copyMigrations(array $migrations): void
    {
        $migrationsSourcePath = __DIR__ . '/../../database/migrations';
        $migrationsDestPath = database_path('migrations');
        $baseTime = time();

        foreach ($migrations as $index => $migrationFile) {
            $srcFile = $migrationsSourcePath . '/' . $migrationFile;
            if (File::exists($srcFile)) {
                $cleanName = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationFile);
                $destFile = $migrationsDestPath . '/' . date('Y_m_d_His', $baseTime + $index) . '_' . $cleanName;

                $exists = false;
                if (File::exists($migrationsDestPath)) {
                    foreach (File::files($migrationsDestPath) as $file) {
                        if (str_contains($file->getFilename(), $cleanName)) {
                            $exists = true;
                            break;
                        }
                    }
                }

                if (! $exists) {
                    File::ensureDirectoryExists($migrationsDestPath);
                    File::copy($srcFile, $destFile);
                    $this->line(" - Published migration [{$cleanName}] to database/migrations/");
                } else {
                    $this->line(" - Migration [{$cleanName}] already exists in database/migrations/");
                }
            }
        }
    }
}
