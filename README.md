# EAM MES Package

This package provides service providers and migrations to easily integrate **Checklist**, **Maintenance**, **Parameter Logs**, and **Error Monitoring** into your Laravel EAM MES application.

It also ships a **Dynamic Table Extension** system that lets consuming applications add extra columns to any package-managed table — without ever touching the package source.

For detailed information about the submodules and the database schema structure, please refer to the [Modules & Database Structure Documentation](docs/modules_and_db.md).

---

## Installation

Add the repository and requirement to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LavioDev/eam-mes-package"
        }
    ],
    "require": {
        "laviodev/eam-mes-package": "dev-main"
    }
}
```

Then install:

```bash
composer update laviodev/eam-mes-package
```

---

## Setup

### 1. Register the Service Provider

The package is auto-discovered by Laravel. If needed, register it manually in `config/app.php`:

```php
Spatie\LaravelPackageTools\EamMesPackageServiceProvider::class,
```

### 2. Publish Config & Run Migrations

```bash
# Publish config/eam.php to your application
php artisan vendor:publish --tag="eam-mes-package-config"

# Run the package base migrations
php artisan migrate
```

### 3. Publish Submodule Code Files

Use the publish command to copy models, actions, and routes for specific submodules into your application:

```bash
# Publish all submodules at once
php artisan eam-mes:publish --all

# Publish a specific submodule
php artisan eam-mes:publish --submodule=core
php artisan eam-mes:publish --submodule=checklist
```

Available submodules: `core`, `checklist`, `error-monitoring`, `maintenance`, `parameter-log`.

---

## Dynamic Table Extension

The extension system lets you **add custom columns** to package-managed tables without modifying the package itself. All changes go through migration files — with full rollback support.

> **Principle**: Never alter schema directly from HTTP requests or `boot()`. Every change must go through a generated migration file.

### Step 1 — Create an Extension Class

```php
<?php

namespace App\Extensions;

use Spatie\LaravelPackageTools\Contracts\TableExtension;
use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

class MaintenancePlanExtension implements TableExtension
{
    public function targetTable(): string
    {
        return 'eamo_maintenance_plans';
    }

    public function columns(): array
    {
        return [
            ColumnDefinition::make('department_id', 'string')
                ->length(36)
                ->nullable()
                ->after('user_id'),

            ColumnDefinition::make('is_urgent', 'boolean')
                ->default(false),

            ColumnDefinition::make('custom_notes', 'text')
                ->nullable(),
        ];
    }

    public function priority(): int
    {
        return 10; // lower = runs first when multiple extensions target the same table
    }
}
```

### Step 2 — Register in `config/eam.php`

```php
return [
    'extensions' => [
        App\Extensions\MaintenancePlanExtension::class,
        App\Extensions\ChecklistDetailExtension::class,
    ],

    'auto_migrate' => env('EAM_AUTO_MIGRATE', false),
];
```

### Step 3 — Generate & Run Migration

```bash
# Preview what will be generated (no files written)
php artisan eam:sync-extensions --dry-run

# Generate migration file(s) in database/migrations/
php artisan eam:sync-extensions

# Generate and run migrate in one step
php artisan eam:sync-extensions --migrate

# Force regeneration even if columns already exist
php artisan eam:sync-extensions --force
```

The command generates a file like:

```
database/migrations/2026_07_05_120000_extend_eamo_maintenance_plans_table.php
```

With complete `up()` and `down()` methods — rollback is always supported.

### Step 4 — Apply the Migration

```bash
php artisan migrate
```

### Rollback

```bash
php artisan migrate:rollback --step=1
```

Because `down()` is automatically generated, rollbacks work transparently through Laravel's standard migration system.

---

### Supported Column Types

| Type | Blueprint Method |
|---|---|
| `string` | `$table->string(name, length)` |
| `integer` | `$table->integer(name)` |
| `bigInteger` | `$table->bigInteger(name)` |
| `boolean` | `$table->boolean(name)` |
| `text` | `$table->text(name)` |
| `longText` | `$table->longText(name)` |
| `json` | `$table->json(name)` |
| `date` | `$table->date(name)` |
| `dateTime` | `$table->dateTime(name)` |
| `timestamp` | `$table->timestamp(name)` |
| `decimal` | `$table->decimal(name)` |
| `float` | `$table->float(name)` |

For more complex changes (rename, change type, foreign keys, composite indexes), write a standard Laravel migration manually.

### Extensible Tables (Whitelist)

Only the following package-managed tables may be extended:

- `eamo_maintenance_plans`
- `eamo_maintenance_schedules`
- `eamo_maintenance_items`
- `eamo_maintenance_categories`
- `eamo_maintenance_logs`
- `eamo_checklist_details`
- `eamo_checklist_sessions`
- `eamo_equipment_parameter_logs`
- `eamo_equipment_error_logs`
- `eamo_operating_times`

---

## HTTP Table Extension API

The package also exposes HTTP API endpoints to programmatically request table extensions. These requests are stored in the database (`eam_extension_requests`), validated, and processed asynchronously via Laravel's Queue system.

### Endpoints

#### 1. Request Column Additions (POST)
- **URL**: `/eam/api/extensions`
- **Method**: `POST`
- **Payload**:
  ```json
  {
    "table": "eamo_maintenance_plans",
    "columns": [
      {
        "name": "department_id",
        "type": "string",
        "length": 36,
        "nullable": true,
        "after": "user_id"
      },
      {
        "name": "is_urgent",
        "type": "boolean",
        "default": false
      }
    ]
  }
  ```
- **Response (202 Accepted)**:
  ```json
  {
    "message": "Extension request queued successfully.",
    "id": 42,
    "status": "queued",
    "table": "eamo_maintenance_plans",
    "columns": ["department_id", "is_urgent"],
    "check_url": "http://yourdomain.com/eam/api/extensions/42"
  }
  ```

#### 2. Check Extension Progress (GET)
- **URL**: `/eam/api/extensions/{id}`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
    "id": 42,
    "table": "eamo_maintenance_plans",
    "status": "done",
    "migration_file": "2026_07_05_120000_extend_eamo_maintenance_plans_table.php",
    "error_message": null,
    "created_at": "2026-07-05T04:15:00.000000Z",
    "updated_at": "2026-07-05T04:16:02.000000Z"
  }
  ```

### Background Processing

Make sure to run a queue worker targeting the `eam-extensions` queue to process requests:

```bash
php artisan queue:work --queue=eam-extensions
```

---

## Production Checklist

| ✅ Do | ❌ Don't |
|---|---|
| Keep `auto_migrate=false` in production | Enable `auto_migrate=true` in production |
| Run `--dry-run` before generating | Skip the preview step |
| Commit generated migration files to git | Generate files directly on the server |
| Run `eam:sync-extensions` in CI/CD pipeline | Trigger schema changes from an HTTP request |
| Test rollback on staging first | Skip rollback testing |

---

## Architecture Overview

```
config/eam.php
  └── ExtensionRegistry::resolve()
        └── ExtensionValidator::validate()
              └── MigrationFileChecker::filterNewColumns()   ← 2-layer duplicate detection
                    └── MigrationGenerator::generate()       ← renders stub → writes file
                          └── php artisan migrate
```

---

## Testing

```bash
composer test
```
