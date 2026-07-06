<?php

/** @var \Spatie\LaravelPackageTools\Tests\EamMesTestCase $this */

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelPackageTools\Jobs\GenerateExtensionMigrationJob;
use Spatie\LaravelPackageTools\Models\ExtensionRequest;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

beforeEach(function () {
    // Run the package base migrations in memory first
    $this->artisan('vendor:publish', [
        '--tag' => 'eam-mes-package-migrations',
    ])->assertSuccessful();

    $this->artisan('migrate')->assertSuccessful();

    // Clean up any generated files in database/migrations
    $migrationPath = database_path('migrations');
    if (File::exists($migrationPath)) {
        foreach (File::files($migrationPath) as $file) {
            if (str_contains($file->getFilename(), 'extend_')) {
                File::delete($file->getPathname());
            }
        }
    }
});

afterEach(function () {
    // Cleanup generated files
    $migrationPath = database_path('migrations');
    if (File::exists($migrationPath)) {
        foreach (File::files($migrationPath) as $file) {
            if (str_contains($file->getFilename(), 'extend_')) {
                File::delete($file->getPathname());
            }
        }
    }
});

it('validates extension payload and returns error for disallowed table', function () {
    $response = $this->postJson('/eam/api/extensions', [
        'table' => 'disallowed_table',
        'columns' => [
            [
                'name' => 'column_a',
                'type' => 'string',
            ],
        ],
    ]);

    $response->assertStatus(422);
    expect($response->json('message'))->toContain('is not in the extension whitelist');
});

it('validates invalid column names', function () {
    $response = $this->postJson('/eam/api/extensions', [
        'table' => 'eamo_maintenance_plans',
        'columns' => [
            [
                'name' => 'Invalid-Column-Name!',
                'type' => 'string',
            ],
        ],
    ]);

    $response->assertStatus(422);
});

it('validates unsupported column types', function () {
    $response = $this->postJson('/eam/api/extensions', [
        'table' => 'eamo_maintenance_plans',
        'columns' => [
            [
                'name' => 'valid_name',
                'type' => 'unsupported_type',
            ],
        ],
    ]);

    $response->assertStatus(422);
});

it('creates log requests and dispatches queue job', function () {
    Queue::fake();

    $response = $this->postJson('/eam/api/extensions', [
        'table' => 'eamo_maintenance_plans',
        'columns' => [
            [
                'name' => 'department_id',
                'type' => 'string',
                'length' => 36,
                'nullable' => true,
                'after' => 'user_id',
            ],
        ],
    ]);

    $response->assertStatus(202);
    expect($response->json('status'))->toEqual('queued');
    expect($response->json('columns'))->toEqual(['department_id']);

    $requestId = $response->json('id');
    expect(ExtensionRequest::find($requestId))->not->toBeNull();

    Queue::assertPushed(GenerateExtensionMigrationJob::class, function ($job) use ($requestId) {
        // Accessing via reflection or directly since Laravel models serialise
        return true;
    });
});

it('returns 200 ok when all columns already exist', function () {
    // Assert helper
    expect(Schema::hasColumn('eamo_maintenance_plans', 'plan_code'))->toBeTrue();

    $response = $this->postJson('/eam/api/extensions', [
        'table' => 'eamo_maintenance_plans',
        'columns' => [
            [
                'name' => 'plan_code',
                'type' => 'string',
            ],
        ],
    ]);

    $response->assertStatus(200);
    expect($response->json('message'))->toContain('already exist');
});

it('executes job correctly and runs migration', function () {
    // Make sure column doesn't exist yet
    expect(Schema::hasColumn('eamo_maintenance_plans', 'department_id'))->toBeFalse();

    // Create request
    $request = ExtensionRequest::create([
        'table_name' => 'eamo_maintenance_plans',
        'columns' => [
            [
                'name' => 'department_id',
                'type' => 'string',
                'length' => 36,
                'nullable' => true,
                'after' => 'user_id',
            ],
        ],
        'status' => 'queued',
    ]);

    $job = new GenerateExtensionMigrationJob($request);
    app()->call([$job, 'handle']);

    // Check database has column
    expect(Schema::hasColumn('eamo_maintenance_plans', 'department_id'))->toBeTrue();

    // Check request state
    $request->refresh();
    expect($request->status)->toEqual('done');
    expect($request->migration_file)->not->toBeNull();
    expect(File::exists(database_path('migrations/' . $request->migration_file)))->toBeTrue();
});
