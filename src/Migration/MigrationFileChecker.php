<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Migration;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

/**
 * Two-layer duplicate detection:
 *
 *  Layer 1 — scan existing migration files for already-declared columns.
 *  Layer 2 — query the live DB for columns that already exist.
 *
 * Both checks are needed because:
 *  - A migration file may exist but not yet have been run (Layer 1 catches it).
 *  - A migration file may have been deleted after running (Layer 2 catches it).
 */
class MigrationFileChecker
{
    /**
     * Filter the given columns down to only those that need a new migration.
     *
     * @param  ColumnDefinition[] $columns
     * @return ColumnDefinition[]
     */
    public function filterNewColumns(string $table, array $columns): array
    {
        $migratedNames  = $this->getAlreadyMigratedColumns($table);
        $existingDbCols = $this->getExistingDbColumns($table);

        return array_values(array_filter(
            $columns,
            fn (ColumnDefinition $col) =>
                ! in_array($col->name, $migratedNames, true) &&
                ! in_array($col->name, $existingDbCols, true)
        ));
    }

    // -------------------------------------------------------------------------
    // Layer 1 — file scan
    // -------------------------------------------------------------------------

    /**
     * Scan database/migrations/ for files matching *extend_{table}_table*
     * and extract column names already declared in those files.
     *
     * @return string[]
     */
    private function getAlreadyMigratedColumns(string $table): array
    {
        $pattern = database_path("migrations/*extend_{$table}_table*");
        $files   = File::glob($pattern);
        $names   = [];

        foreach ($files as $file) {
            $content = File::get($file);

            // Match: $table->someType('column_name'
            preg_match_all('/\$table->\w+\(\'(\w+)\'/', $content, $matches);

            // Exclude Blueprint methods that are not column additions
            $excluded = ['dropColumn', 'dropForeign', 'dropIndex', 'dropUnique', 'dropPrimary'];
            foreach ($matches[1] as $name) {
                if (! in_array($name, $excluded, true)) {
                    $names[] = $name;
                }
            }
        }

        return array_unique($names);
    }

    // -------------------------------------------------------------------------
    // Layer 2 — live DB
    // -------------------------------------------------------------------------

    /**
     * Retrieve actual column names from the database.
     * Returns an empty array if the table does not yet exist.
     *
     * @return string[]
     */
    private function getExistingDbColumns(string $table): array
    {
        try {
            if (! Schema::hasTable($table)) {
                return [];
            }

            return Schema::getColumnListing($table);
        } catch (\Exception) {
            // DB may be unavailable in some CI environments — fail silently.
            return [];
        }
    }
}
