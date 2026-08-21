<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Modules;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class AbstractModuleProvider extends ServiceProvider
{
    /**
     * Get domain name (e.g. Equipment, Masterdata).
     */
    abstract public function getDomain(): string;

    /**
     * Get submodule name (e.g. Checklist, Maintenance).
     */
    abstract public function getName(): string;

    /**
     * Get unique identifier in dot notation (e.g. equipment.checklist).
     */
    public function getIdentifier(): string
    {
        return strtolower($this->getDomain() . '.' . $this->getName());
    }

    /**
     * Get absolute path to the module directory.
     */
    public function getModulePath(): string
    {
        return dirname((new ReflectionClass(static::class))->getFileName());
    }

    /**
     * Get path to the module's routes file if it exists.
     */
    public function getRoutePath(): ?string
    {
        $path = $this->getModulePath() . '/routes.php';

        return file_exists($path) ? $path : null;
    }

    /**
     * Get list of migration files belonging to this module.
     * Migration filenames are relative to package/database/migrations/.
     *
     * @return array<int, string>
     */
    public function getMigrations(): array
    {
        return [];
    }

    public function boot(): void
    {
        if ($routePath = $this->getRoutePath()) {
            $this->loadRoutesFrom($routePath);
        }

        $this->bootModule();
    }

    /**
     * Hook for subclasses to perform boot actions.
     */
    protected function bootModule(): void
    {
        // Override in child classes if needed
    }
}
