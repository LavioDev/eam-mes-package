<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Extensions;

use Spatie\LaravelPackageTools\Exceptions\InvalidExtensionException;

/**
 * Immutable value object describing a single database column.
 *
 * Use the fluent static factory for readability:
 *
 *   ColumnDefinition::make('department_id', 'string')
 *       ->length(36)
 *       ->nullable()
 *       ->after('user_id');
 */
final class ColumnDefinition
{
    /**
     * All column types that can be rendered by StubRenderer.
     */
    public const SUPPORTED_TYPES = [
        'string',
        'integer',
        'bigInteger',
        'unsignedInteger',
        'unsignedBigInteger',
        'boolean',
        'text',
        'longText',
        'mediumText',
        'json',
        'jsonb',
        'date',
        'dateTime',
        'timestamp',
        'decimal',
        'float',
        'double',
        'tinyInteger',
        'smallInteger',
    ];

    public function __construct(
        public readonly string  $name,
        public readonly string  $type,
        public readonly bool    $nullable = true,
        public readonly mixed   $default = null,
        public readonly ?int    $length = null,
        public readonly ?string $after = null,
        public readonly bool    $unsigned = false,
        public readonly ?int    $precision = null,
        public readonly ?int    $scale = null,
    ) {
    }

    // -------------------------------------------------------------------------
    // Factory
    // -------------------------------------------------------------------------

    public static function make(string $name, string $type): static
    {
        return new static($name, $type);
    }

    // -------------------------------------------------------------------------
    // Fluent modifiers — each returns a new immutable instance
    // -------------------------------------------------------------------------

    public function nullable(bool $value = true): static
    {
        return new static(
            $this->name, $this->type, $value, $this->default,
            $this->length, $this->after, $this->unsigned,
            $this->precision, $this->scale,
        );
    }

    public function notNullable(): static
    {
        return $this->nullable(false);
    }

    public function default(mixed $value): static
    {
        return new static(
            $this->name, $this->type, $this->nullable, $value,
            $this->length, $this->after, $this->unsigned,
            $this->precision, $this->scale,
        );
    }

    public function length(int $length): static
    {
        return new static(
            $this->name, $this->type, $this->nullable, $this->default,
            $length, $this->after, $this->unsigned,
            $this->precision, $this->scale,
        );
    }

    public function after(string $column): static
    {
        return new static(
            $this->name, $this->type, $this->nullable, $this->default,
            $this->length, $column, $this->unsigned,
            $this->precision, $this->scale,
        );
    }

    public function unsigned(): static
    {
        return new static(
            $this->name, $this->type, $this->nullable, $this->default,
            $this->length, $this->after, true,
            $this->precision, $this->scale,
        );
    }

    public function precision(int $precision, int $scale = 2): static
    {
        return new static(
            $this->name, $this->type, $this->nullable, $this->default,
            $this->length, $this->after, $this->unsigned,
            $precision, $scale,
        );
    }

    // -------------------------------------------------------------------------
    // Validation
    // -------------------------------------------------------------------------

    public function validate(): void
    {
        if (! preg_match('/^[a-z][a-z0-9_]*$/', $this->name)) {
            throw InvalidExtensionException::invalidColumnName($this->name);
        }

        if (! in_array($this->type, self::SUPPORTED_TYPES, true)) {
            throw InvalidExtensionException::unsupportedColumnType($this->type);
        }
    }
}
