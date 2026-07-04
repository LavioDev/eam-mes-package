<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Migration;

use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

/**
 * Converts ColumnDefinition objects into PHP source lines
 * and replaces placeholders in the stub template.
 */
class StubRenderer
{
    /**
     * Render a single ColumnDefinition into a Blueprint up() line.
     */
    public function renderUpColumn(ColumnDefinition $col): string
    {
        $line = "\$table->{$col->type}('{$col->name}'";

        // Append length parameter for string columns
        if ($col->type === 'string' && $col->length !== null) {
            $line .= ", {$col->length}";
        }

        // Append precision/scale for decimal columns
        if (in_array($col->type, ['decimal', 'double', 'float'], true)) {
            if ($col->precision !== null) {
                $line .= ", {$col->precision}";
                if ($col->scale !== null) {
                    $line .= ", {$col->scale}";
                }
            }
        }

        $line .= ')';

        if ($col->nullable) {
            $line .= '->nullable()';
        }

        if ($col->default !== null) {
            $line .= '->default(' . $this->renderDefaultValue($col->default) . ')';
        }

        if ($col->unsigned) {
            $line .= '->unsigned()';
        }

        if ($col->after !== null) {
            $line .= "->after('{$col->after}')";
        }

        return '            ' . $line . ';';
    }

    /**
     * Render a single ColumnDefinition into a Blueprint down() line.
     */
    public function renderDownColumn(ColumnDefinition $col): string
    {
        return "            \$table->dropColumn('{$col->name}');";
    }

    /**
     * Render the full stub file content.
     *
     * @param  ColumnDefinition[] $columns
     * @param  string[]           $extensionClasses
     */
    public function render(
        string $stubContent,
        string $table,
        array $columns,
        array $extensionClasses = [],
    ): string {
        $upLines   = array_map([$this, 'renderUpColumn'], $columns);
        $downLines = array_map([$this, 'renderDownColumn'], $columns);

        return str_replace(
            [
                '{{ table }}',
                '{{ up_columns }}',
                '{{ down_columns }}',
                '{{ extension_classes }}',
                '{{ generated_at }}',
            ],
            [
                $table,
                implode("\n", $upLines),
                implode("\n", $downLines),
                implode(', ', $extensionClasses),
                now()->toDateTimeString(),
            ],
            $stubContent
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function renderDefaultValue(mixed $value): string
    {
        return match (true) {
            is_string($value) => "'{$value}'",
            is_bool($value)   => $value ? 'true' : 'false',
            is_null($value)   => 'null',
            default           => var_export($value, true),
        };
    }
}
