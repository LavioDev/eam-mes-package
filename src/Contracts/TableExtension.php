<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Contracts;

use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

interface TableExtension
{
    /**
     * The name of the table to extend.
     * Must be in the package whitelist.
     */
    public function targetTable(): string;

    /**
     * List of columns to add to the table.
     *
     * @return ColumnDefinition[]
     */
    public function columns(): array;

    /**
     * Priority for ordering when multiple extensions target the same table.
     * Lower value = runs first.
     */
    public function priority(): int;
}
