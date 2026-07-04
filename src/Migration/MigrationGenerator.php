<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Migration;

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Contracts\TableExtension;
use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

class MigrationGenerator
{
    public function __construct(
        private readonly StubRenderer $renderer,
    ) {
    }

    /**
     * Render the stub and write the migration file to database/migrations/.
     *
     * File naming convention:
     *   {timestamp}_extend_{table}_table.php
     *
     * @param  ColumnDefinition[]  $columns
     * @param  TableExtension[]    $extensions  Used to embed class names in the file header comment.
     *
     * @return string  Absolute path of the generated file.
     */
    public function generate(string $table, array $columns, array $extensions = []): string
    {
        $stubPath = __DIR__ . '/../../database/stubs/add_columns.stub';
        $stub     = File::get($stubPath);

        $extensionClasses = array_map(
            fn (TableExtension $ext) => get_class($ext),
            array_values($extensions)
        );

        $rendered = $this->renderer->render($stub, $table, $columns, $extensionClasses);

        $fileName    = date('Y_m_d_His') . "_extend_{$table}_table.php";
        $destination = database_path("migrations/{$fileName}");

        File::ensureDirectoryExists(database_path('migrations'));
        File::put($destination, $rendered);

        return $destination;
    }
}
