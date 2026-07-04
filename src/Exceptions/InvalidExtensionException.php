<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Exceptions;

use RuntimeException;

class InvalidExtensionException extends RuntimeException
{
    public static function classNotFound(string $class): static
    {
        return new static("Extension class [{$class}] does not exist.");
    }

    public static function doesNotImplementInterface(string $class): static
    {
        return new static(
            "Extension class [{$class}] must implement " .
            \Spatie\LaravelPackageTools\Contracts\TableExtension::class . '.'
        );
    }

    public static function tableNotAllowed(string $table, array $allowed): static
    {
        $list = implode(', ', $allowed);

        return new static(
            "Table [{$table}] is not in the extension whitelist. " .
            "Allowed tables: {$list}."
        );
    }

    public static function invalidColumnName(string $name): static
    {
        return new static(
            "Column name [{$name}] is invalid. " .
            'Only lowercase letters, digits, and underscores are allowed.'
        );
    }

    public static function unsupportedColumnType(string $type): static
    {
        $supported = implode(', ', \Spatie\LaravelPackageTools\Extensions\ColumnDefinition::SUPPORTED_TYPES);

        return new static(
            "Column type [{$type}] is not supported. " .
            "Supported types: {$supported}."
        );
    }
}
