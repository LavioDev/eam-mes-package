<?php

declare(strict_types=1);

namespace Modules\\Equipment\\Management;

use App\Providers\IModuleProvider;
use Illuminate\Support\ServiceProvider;

final class Register extends ServiceProvider implements IModuleProvider
{
    public function seed(): void
    {
        // No seeders
    }

    public function getRoutePath(): string
    {
        return __DIR__ . '/routes.php';
    }

    public function getMigrationPath(): string
    {
        return __DIR__ . '/Migrations';
    }

    public function registerPolicies(): void
    {
        // No policies
    }

    public function boot(): void
    {
        // No boot logic
    }
}


