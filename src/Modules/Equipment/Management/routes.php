<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\Management\Actions\IotLog\IndexIotLogAction;
use Modules\Equipment\Management\Actions\IotLog\ShowIotLogAction;

Route::group([], function (): void {
    Route::prefix('v1/iot-logs')->name('iot-logs.')->group(function (): void {
        Route::get('/', IndexIotLogAction::class)->name('index');
        Route::get('/{id}', ShowIotLogAction::class)->name('show');
    });
});

