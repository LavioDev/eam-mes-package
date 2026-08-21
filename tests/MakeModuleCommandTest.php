<?php

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Tests\EamMesTestCase;

uses(EamMesTestCase::class);

afterEach(function () {
    File::deleteDirectory(dirname(__DIR__) . '/src/Modules/TestDomain');

    $migrationsPath = dirname(__DIR__) . '/database/migrations';
    if (File::exists($migrationsPath)) {
        foreach (File::files($migrationsPath) as $file) {
            if (str_contains($file->getFilename(), 'test_submodules')) {
                File::delete($file->getPathname());
            }
        }
    }
});

it('can generate a new module via eam:make-module command', function () {
    $this->artisan('eam:make-module', [
        'domain' => 'TestDomain',
        'submodule' => 'TestSubmodule',
        '--crud' => true,
    ])->assertSuccessful();

    $moduleDir = dirname(__DIR__) . '/src/Modules/TestDomain/TestSubmodule';

    expect(File::exists("{$moduleDir}/Register.php"))->toBeTrue();
    expect(File::exists("{$moduleDir}/routes.php"))->toBeTrue();
    expect(File::exists("{$moduleDir}/Models/TestSubmodule.php"))->toBeTrue();
    expect(File::exists("{$moduleDir}/Services/TestSubmoduleService.php"))->toBeTrue();
    expect(File::exists("{$moduleDir}/Requests/StoreTestSubmoduleRequest.php"))->toBeTrue();
    expect(File::exists("{$moduleDir}/Actions/IndexTestSubmoduleAction.php"))->toBeTrue();
});
