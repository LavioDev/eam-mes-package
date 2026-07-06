<?php

/** @var \Spatie\LaravelPackageTools\Tests\EamMesTestCase $this */

use Illuminate\Support\Facades\Schema;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

it('can run all eamo migrations', function () {
    // Publish migrations first
    $this->artisan('vendor:publish', [
        '--tag' => 'eam-mes-package-migrations',
    ])->assertSuccessful();

    // Run migrations
    $this->artisan('migrate')->assertSuccessful();

    $tables = [
        'eamo_checklist_details',
        'eamo_checklist_sessions',
        'eamo_equipment_parameter_logs',
        'eamo_equipment_error_logs',
        'eamo_maintenance_plans',
        'eamo_maintenance_schedules',
        'eamo_maintenance_items',
        'eamo_maintenance_categories',
        'eamo_maintenance_logs',
        'eamo_operating_times',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue("Table {$table} does not exist.");
    }
});
