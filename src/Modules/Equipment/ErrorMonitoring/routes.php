<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\ErrorMonitoring\Actions\DeleteEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\EquipmentErrorLogChartAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexEquipmentStopRateAction;
use Modules\Equipment\ErrorMonitoring\Actions\ShowEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\StoreEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\UpdateEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexStockOeeChartAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexStockOeeHomeChartAction;
use Modules\Equipment\ErrorMonitoring\Actions\OperatingTimeChartAction;
use Modules\Equipment\ErrorMonitoring\Actions\SaveEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Actions\DeleteOperatingTimeAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexOperatingTimeAction;
use Modules\Equipment\ErrorMonitoring\Actions\IndexOperatingTimeChartAction;
use Modules\Equipment\ErrorMonitoring\Actions\ShowOperatingTimeAction;
use Modules\Equipment\ErrorMonitoring\Actions\StoreOperatingTimeAction;
use Modules\Equipment\ErrorMonitoring\Actions\UpdateOperatingTimeAction;

Route::group([], function (): void {
    Route::prefix('v1/equipment/error-monitoring/equipment-error-logs')->name('equipment-error-logs.')->group(function (): void {
        Route::get('/', IndexEquipmentErrorLogAction::class)->name('index');
        Route::post('/', StoreEquipmentErrorLogAction::class)->name('store');
        Route::get('/oee', IndexStockOeeChartAction::class)->name('oee');
        Route::get('/oee-home', IndexStockOeeHomeChartAction::class)->name('oee-home');
        Route::get('/chart', EquipmentErrorLogChartAction::class)->name('chart');
        Route::get('/{id}', ShowEquipmentErrorLogAction::class)->name('show');
        Route::put('/{id}', UpdateEquipmentErrorLogAction::class)->name('update');
        Route::delete('/{id}', DeleteEquipmentErrorLogAction::class)->name('destroy');

        Route::post('/save', SaveEquipmentErrorLogAction::class)->name('save');
    });

    Route::prefix('v1/equipment/error-monitoring/operating-times')->name('operating-times.')->group(function (): void {
        Route::get('/', IndexOperatingTimeAction::class)->name('index');
        Route::post('/', StoreOperatingTimeAction::class)->name('store');
        Route::get('/chart', IndexOperatingTimeChartAction::class)->name('chart');
        Route::get('/{id}', ShowOperatingTimeAction::class)->name('show');
        Route::put('/{id}', UpdateOperatingTimeAction::class)->name('update');
        Route::delete('/{id}', DeleteOperatingTimeAction::class)->name('destroy');
    });

    // Route::prefix('v1/equipment/error-monitoring/statistical/')->name('error-monitoring-statistical')->group(function (): void {
    //     Route::get('stop-error-rate', IndexEquipmentStopRateAction::class)->name('stop-error-rate');
    // });
});
