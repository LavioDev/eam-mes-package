<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Commands;

use Illuminate\Console\Command;
use Spatie\LaravelPackageTools\Extensions\ExtensionRegistry;
use Spatie\LaravelPackageTools\Extensions\ExtensionValidator;
use Spatie\LaravelPackageTools\Migration\MigrationFileChecker;
use Spatie\LaravelPackageTools\Migration\MigrationGenerator;

class SyncExtensionsCommand extends Command
{
    protected $signature = 'eam:sync-extensions
                            {--migrate   : Run `php artisan migrate` after generating files}
                            {--dry-run   : Preview changes without writing any files}
                            {--force     : Skip duplicate detection and regenerate all files}';

    protected $description = 'Generate migration files from TableExtension classes registered in config/eam.php';

    public function __construct(
        private readonly MigrationFileChecker $checker,
        private readonly MigrationGenerator   $generator,
        private readonly ExtensionValidator   $validator,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Reading extensions from config/eam.php...');

        // ── 1. Resolve ────────────────────────────────────────────────────────
        try {
            $grouped = ExtensionRegistry::resolve(); // Collection keyed by table
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ($grouped->isEmpty()) {
            $this->warn('No extensions registered. Add classes to config/eam.php under the "extensions" key.');

            return self::SUCCESS;
        }

        $generatedFiles = [];

        foreach ($grouped as $table => $extensions) {
            $this->newLine();
            $this->line("<fg=cyan;options=bold>Table: {$table}</>");

            // ── 2. Collect columns from all extensions targeting this table ───
            $allColumns = collect($extensions)
                ->flatMap(fn ($ext) => $ext->columns())
                ->values()
                ->all();

            // ── 3. Validate ───────────────────────────────────────────────────
            try {
                $this->validator->validate($table, $allColumns);
            } catch (\Exception $e) {
                $this->warn("  ⚠  {$e->getMessage()}");
                continue;
            }

            // ── 4. Duplicate detection ────────────────────────────────────────
            $newColumns = $this->option('force')
                ? $allColumns
                : $this->checker->filterNewColumns($table, $allColumns);

            if (empty($newColumns)) {
                $this->line('  ✓  All columns already exist. Skipping.');
                continue;
            }

            // ── 5. Preview table ──────────────────────────────────────────────
            $this->table(
                ['Column', 'Type', 'Nullable', 'Default', 'After'],
                collect($newColumns)->map(fn ($col) => [
                    $col->name,
                    $col->type . ($col->length ? "({$col->length})" : ''),
                    $col->nullable ? 'true' : 'false',
                    $col->default !== null ? var_export($col->default, true) : 'null',
                    $col->after ?? '—',
                ])->all()
            );

            if ($this->option('dry-run')) {
                $this->info("  [DRY-RUN] Would generate migration for table [{$table}].");
                continue;
            }

            // ── 6. Generate migration file ────────────────────────────────────
            try {
                $filePath         = $this->generator->generate($table, $newColumns, $extensions->all());
                $generatedFiles[] = $filePath;
                $this->info('  ✓  Generated: ' . basename($filePath));
            } catch (\Exception $e) {
                $this->error("  ✗  Failed to generate migration: {$e->getMessage()}");

                return self::FAILURE;
            }
        }

        // ── 7. Optionally run migrate ─────────────────────────────────────────
        $shouldMigrate = $this->option('migrate')
            || config('eam.auto_migrate', false);

        if (! empty($generatedFiles)) {
            $this->newLine();

            if ($shouldMigrate) {
                $this->info('Running php artisan migrate...');
                $this->call('migrate');
            } else {
                $this->info(
                    'Run <fg=yellow>php artisan migrate</> to apply the generated migration(s).'
                );
            }
        }

        return self::SUCCESS;
    }
}
