<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ModuleRegistry
{
    /**
     * @var Collection<string, AbstractModuleProvider>
     */
    protected Collection $modules;

    public function __construct()
    {
        $this->modules = collect();
    }

    public function register(AbstractModuleProvider $provider): void
    {
        $this->modules->put($provider->getIdentifier(), $provider);
    }

    /**
     * Auto-discover all modules inside the specified base directory.
     */
    public function discover(string $basePath): void
    {
        if (! File::isDirectory($basePath)) {
            return;
        }

        $registerFiles = File::glob($basePath . '/*/*/Register.php');

        foreach ($registerFiles as $file) {
            $relativePath = str_replace([$basePath . '/', '/Register.php', '\\'], ['', '', '/'], $file);
            $parts = explode('/', $relativePath);

            if (count($parts) === 2) {
                [$domain, $submodule] = $parts;
                $className = "Modules\\{$domain}\\{$submodule}\\Register";

                if (class_exists($className) && is_subclass_of($className, AbstractModuleProvider::class)) {
                    /** @var AbstractModuleProvider $provider */
                    $provider = new $className(app());
                    $this->register($provider);
                }
            }
        }
    }

    /**
     * Get all registered modules.
     *
     * @return Collection<string, AbstractModuleProvider>
     */
    public function all(): Collection
    {
        return $this->modules;
    }

    /**
     * Get active (enabled) modules according to configuration.
     *
     * @return Collection<string, AbstractModuleProvider>
     */
    public function enabled(): Collection
    {
        $disabled = (array) config('eam.modules.disabled', []);

        return $this->modules->reject(function (AbstractModuleProvider $provider) use ($disabled) {
            return in_array($provider->getIdentifier(), $disabled, true);
        });
    }

    /**
     * Find a registered module by its identifier (e.g. equipment.checklist).
     */
    public function get(string $identifier): ?AbstractModuleProvider
    {
        return $this->modules->get(strtolower($identifier));
    }
}
