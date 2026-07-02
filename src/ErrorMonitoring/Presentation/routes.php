<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\DeleteEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\EquipmentErrorLogChartAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\IndexEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\IndexEquipmentStopRateAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\ShowEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\StoreEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\UpdateEquipmentErrorLogAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\IndexStockOeeChartAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\IndexStockOeeHomeChartAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\OperatingTimeChartAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\SaveEquipmentErrorLogAction;

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

    // Route::prefix('v1/equipment/error-monitoring/statistical/')->name('error-monitoring-statistical')->group(function (): void {
    //     Route::get('stop-error-rate', IndexEquipmentStopRateAction::class)->name('stop-error-rate');
    // });
});
