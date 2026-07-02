# EAM MES Package

This package provides service providers and migrations to easily integrate Checklist, Maintenance, Parameter Logs, and Error Monitoring into your Laravel EAM MES application.

For detailed information about the submodules and the database schema structure, please refer to the [Modules & Database Structure Documentation](docs/modules_and_db.md).

## Installation

You can install the package via composer by adding the repository VCS configuration and requirement to your `composer.json` file:

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

Then execute the composer update command:

```bash
composer update laviodev/eam-mes-package
```

## Setup

1. **Register Service Provider**:
   Register the service provider class in your main Laravel application (e.g., in `config/app.php` under `providers` or via auto-discovery):
   ```php
   Spatie\LaravelPackageTools\EamMesPackageServiceProvider::class
   ```

2. **Publish Submodules (Code & Migrations)**:
   You can selectively publish the code files (models, actions, routes) and database migrations for specific submodules (or all at once) into your main application using the custom Artisan command:

   * **Publish all submodules at once**:
     ```bash
     php artisan eam-mes:publish --all
     ```

   * **Publish a specific submodule (e.g., Checklist)**:
     ```bash
     php artisan eam-mes:publish --submodule=checklist
     ```
     Available submodules: `checklist`, `error-monitoring`, `maintenance`, `parameter-log`, `thingsboard`.

3. **Run Migrations**:
   Run the standard Laravel migration command to create the database tables:
   ```bash
   php artisan migrate
   ```
