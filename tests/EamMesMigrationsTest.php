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
        'eamo_equipment',
        'eamo_equipment_parameters',
        'eamo_standard_parameters',
        'eamo_equipment_errors',
        'eamo_equipment_equipment_errors',
        'eamo_iot_logs',
        'eamo_extension_requests',
        'eamo_equipment_categories',
    ];

    foreach ($tables as $table) {
        if ($table === 'eamo_iot_logs') {
            expect(Schema::connection('pgsql_log')->hasTable($table))->toBeTrue("Table {$table} does not exist on connection pgsql_log.");
        } else {
            expect(Schema::hasTable($table))->toBeTrue("Table {$table} does not exist.");
        }
    }
});
