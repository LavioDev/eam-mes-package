<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Extensions;

use Illuminate\Support\Collection;
use Spatie\LaravelPackageTools\Contracts\TableExtension;
use Spatie\LaravelPackageTools\Exceptions\InvalidExtensionException;

class ExtensionRegistry
{
    /**
     * Whitelist of tables that are allowed to be extended.
     * Only tables managed by this package appear here.
     */
    protected static array $allowedTables = [
        'eamo_maintenance_plans',
        'eamo_maintenance_schedules',
        'eamo_maintenance_items',
        'eamo_maintenance_categories',
        'eamo_maintenance_logs',
        'eamo_checklist_details',
        'eamo_checklist_sessions',
        'eamo_equipment_parameter_logs',
        'eamo_equipment_error_logs',
        'eamo_operating_times',
    ];

    /**
     * Read extension class names from config('eam.extensions'),
     * instantiate them (supports Laravel DI), validate, sort by priority,
     * and group by target table name.
     *
     * @return Collection<string, TableExtension[]>
     *
     * @throws InvalidExtensionException
     */
    public static function resolve(): Collection
    {
        $classes = config('eam.extensions', []);

        return collect($classes)
            ->map(function (string $class): TableExtension {
                if (! class_exists($class)) {
                    throw InvalidExtensionException::classNotFound($class);
                }

                $instance = app($class);

                if (! ($instance instanceof TableExtension)) {
                    throw InvalidExtensionException::doesNotImplementInterface($class);
                }

                return $instance;
            })
            ->sortBy(fn (TableExtension $ext) => $ext->priority())
            ->groupBy(fn (TableExtension $ext) => $ext->targetTable());
    }

    /**
     * Assert that a table name is in the package whitelist.
     *
     * @throws InvalidExtensionException
     */
    public static function validateTable(string $table): void
    {
        if (! in_array($table, static::$allowedTables, true)) {
            throw InvalidExtensionException::tableNotAllowed($table, static::$allowedTables);
        }
    }

    public static function getAllowedTables(): array
    {
        return static::$allowedTables;
    }
}
