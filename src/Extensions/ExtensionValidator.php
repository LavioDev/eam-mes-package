<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Extensions;

use Spatie\LaravelPackageTools\Exceptions\InvalidExtensionException;

class ExtensionValidator
{
    /**
     * Validate all ColumnDefinitions for a given table.
     *
     * @param  ColumnDefinition[] $columns
     *
     * @throws InvalidExtensionException
     */
    public function validate(string $table, array $columns): void
    {
        ExtensionRegistry::validateTable($table);

        foreach ($columns as $column) {
            $column->validate();
        }

        $this->assertNoDuplicateColumnNames($columns);
    }

    /**
     * Ensure no two ColumnDefinitions in the same batch share a name.
     *
     * @param  ColumnDefinition[] $columns
     *
     * @throws InvalidExtensionException
     */
    private function assertNoDuplicateColumnNames(array $columns): void
    {
        $names = array_map(fn (ColumnDefinition $c) => $c->name, $columns);
        $duplicates = array_filter(array_count_values($names), fn (int $count) => $count > 1);

        if (! empty($duplicates)) {
            $list = implode(', ', array_keys($duplicates));

            throw new InvalidExtensionException(
                "Duplicate column names detected across extensions: [{$list}]."
            );
        }
    }
}
