<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'eam:make-module
                            {domain : The domain name (e.g. Equipment, Masterdata)}
                            {submodule : The submodule name (e.g. Tooling, Checklist)}
                            {--model= : Name of the main Eloquent Model (defaults to submodule name)}
                            {--crud : Generate full CRUD Actions, Form Requests, and Service}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate a new EAM-MES module scaffold with Register provider, routes, models, and actions';

    public function handle(): int
    {
        $domain = Str::studly((string) $this->argument('domain'));
        $submodule = Str::studly((string) $this->argument('submodule'));
        $model = Str::studly((string) ($this->option('model') ?: $submodule));

        $namespace = "Modules\\{$domain}\\{$submodule}";
        $moduleDir = __DIR__ . "/../Modules/{$domain}/{$submodule}";
        $tableName = 'eamo_' . Str::snake(Str::pluralStudly($model));
        $migrationFileName = date('Y_m_d_His') . "_eamo_create_{$tableName}_table.php";

        $routePrefix = Str::kebab(Str::pluralStudly($model));

        $replacements = [
            '{{ domain }}' => $domain,
            '{{ submodule }}' => $submodule,
            '{{ namespace }}' => $namespace,
            '{{ model }}' => $model,
            '{{ table }}' => $tableName,
            '{{ route_prefix }}' => $routePrefix,
            '{{ migration_file }}' => $migrationFileName,
        ];

        $this->info("Creating module [{$domain}/{$submodule}]...");

        // 1. Create Register.php
        $this->generateFile(
            __DIR__ . '/../../stubs/module/register.stub',
            "{$moduleDir}/Register.php",
            $replacements
        );

        // 2. Create routes.php
        $this->generateFile(
            __DIR__ . '/../../stubs/module/routes.stub',
            "{$moduleDir}/routes.php",
            $replacements
        );

        // 3. Create Model
        $this->generateFile(
            __DIR__ . '/../../stubs/module/model.stub',
            "{$moduleDir}/Models/{$model}.php",
            $replacements
        );

        // 4. Create Migration
        $migrationPath = __DIR__ . "/../../database/migrations/{$migrationFileName}";
        $this->generateFile(
            __DIR__ . '/../../stubs/module/migration.create.stub',
            $migrationPath,
            $replacements
        );

        // 5. If --crud option is set, generate Actions, Requests, and Service
        if ($this->option('crud')) {
            // Requests
            $this->generateFile(
                __DIR__ . '/../../stubs/module/request.store.stub',
                "{$moduleDir}/Requests/Store{$model}Request.php",
                $replacements
            );
            $this->generateFile(
                __DIR__ . '/../../stubs/module/request.update.stub',
                "{$moduleDir}/Requests/Update{$model}Request.php",
                $replacements
            );

            // Service
            $this->generateFile(
                __DIR__ . '/../../stubs/module/service.stub',
                "{$moduleDir}/Services/{$model}Service.php",
                $replacements
            );

            // Actions
            $actions = ['index', 'store', 'show', 'update', 'delete'];
            foreach ($actions as $action) {
                $actionStudly = Str::studly($action);
                $this->generateFile(
                    __DIR__ . "/../../stubs/module/action.{$action}.stub",
                    "{$moduleDir}/Actions/{$actionStudly}{$model}Action.php",
                    $replacements
                );
            }
        }

        $this->newLine();
        $this->info("✓ Module [{$domain}/{$submodule}] generated successfully.");
        $this->line("  - Provider:  [{$namespace}\\Register]");
        $this->line("  - Route:     [{$moduleDir}/routes.php]");
        $this->line("  - Model:     [{$namespace}\\Models\\{$model}]");
        $this->line("  - Migration: [database/migrations/{$migrationFileName}]");

        return self::SUCCESS;
    }

    protected function generateFile(string $stubPath, string $targetPath, array $replacements): void
    {
        if (! File::exists($stubPath)) {
            $this->error("Stub file not found at: {$stubPath}");
            return;
        }

        if (File::exists($targetPath) && ! $this->option('force')) {
            $this->line("  - Skipped existing file: " . basename($targetPath));
            return;
        }

        $content = File::get($stubPath);
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        File::ensureDirectoryExists(dirname($targetPath));
        File::put($targetPath, $content);
        $this->line("  - Created: " . basename($targetPath));
    }
}
