<?php

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

beforeEach(function () {
    File::deleteDirectory(base_path('modules/Equipment'));
    File::deleteDirectory(database_path('migrations'));
});

afterEach(function () {
    File::deleteDirectory(base_path('modules/Equipment'));
    File::deleteDirectory(database_path('migrations'));
});

it('can publish a specific submodule checklist', function () {
    $this->artisan('eam-mes:publish', [
        '--submodule' => 'checklist',
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Equipment/Checklist/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Checklist/Presentation/routes.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(3);
});

it('can publish all submodules', function () {
    $this->artisan('eam-mes:publish', [
        '--all' => true,
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Equipment/Checklist/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/ErrorMonitoring/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Maintenance/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/ParameterLog/Register.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(10);
});
