<?php

use Spatie\LaravelPackageTools\Modules\ModuleRegistry;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

it('can discover all submodules in package', function () {
    /** @var ModuleRegistry $registry */
    $registry = app(ModuleRegistry::class);

    $modules = $registry->all();

    expect($modules->has('equipment.checklist'))->toBeTrue();
    expect($modules->has('equipment.errormonitoring'))->toBeTrue();
    expect($modules->has('equipment.maintenance'))->toBeTrue();
    expect($modules->has('equipment.parameterlog'))->toBeTrue();
    expect($modules->has('masterdata.equipment'))->toBeTrue();
});

it('can get module by identifier', function () {
    /** @var ModuleRegistry $registry */
    $registry = app(ModuleRegistry::class);

    $checklist = $registry->get('equipment.checklist');

    expect($checklist)->not->toBeNull();
    expect($checklist->getDomain())->toBe('Equipment');
    expect($checklist->getName())->toBe('Checklist');
    expect(count($checklist->getMigrations()))->toBe(3);
});
