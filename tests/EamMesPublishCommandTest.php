<?php

/** @var \Spatie\LaravelPackageTools\Tests\EamMesTestCase $this */

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

beforeEach(function () {
    File::deleteDirectory(base_path('modules'));
    File::deleteDirectory(database_path('migrations'));
});

afterEach(function () {
    File::deleteDirectory(base_path('modules'));
    File::deleteDirectory(database_path('migrations'));
});

it('can publish a specific submodule checklist', function () {
    $this->artisan('eam-mes:publish', [
        '--submodule' => 'checklist',
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Equipment/Checklist/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Checklist/routes.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(3);
});

it('can publish a specific submodule equipment', function () {
    $this->artisan('eam-mes:publish', [
        '--submodule' => 'equipment',
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Equipment/Management/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Management/routes.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(2);
});

it('can publish a specific submodule masterdata-equipment', function () {
    $this->artisan('eam-mes:publish', [
        '--submodule' => 'masterdata-equipment',
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Masterdata/Equipment/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Masterdata/Equipment/routes.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(5);
});

it('can publish all submodules', function () {
    $this->artisan('eam-mes:publish', [
        '--all' => true,
    ])->assertSuccessful();

    expect(File::exists(base_path('modules/Equipment/Checklist/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/ErrorMonitoring/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Maintenance/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/ParameterLog/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Equipment/Management/Register.php')))->toBeTrue();
    expect(File::exists(base_path('modules/Masterdata/Equipment/Register.php')))->toBeTrue();

    $migrationFiles = File::files(database_path('migrations'));
    expect(count($migrationFiles))->toBe(18);
});


