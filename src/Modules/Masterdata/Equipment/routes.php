<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Masterdata\Equipment\Models\Equipment;
use Modules\Masterdata\Equipment\Models\EquipmentCategory;
use Modules\Masterdata\Equipment\Models\EquipmentError;
use Modules\Masterdata\Equipment\Models\EquipmentParameter;
use Modules\Masterdata\Equipment\Models\StandardParameter;
use Modules\Masterdata\Equipment\Actions\DeleteEquipmentAction;
use Modules\Masterdata\Equipment\Actions\DeleteEquipmentCategoryAction;
use Modules\Masterdata\Equipment\Actions\DeleteEquipmentErrorAction;
use Modules\Masterdata\Equipment\Actions\DeleteEquipmentParameterAction;
use Modules\Masterdata\Equipment\Actions\DeleteStandardParameterAction;
use Modules\Masterdata\Equipment\Actions\IndexEquipmentAction;
use Modules\Masterdata\Equipment\Actions\IndexEquipmentCategoryAction;
use Modules\Masterdata\Equipment\Actions\IndexEquipmentErrorAction;
use Modules\Masterdata\Equipment\Actions\IndexEquipmentParameterAction;
use Modules\Masterdata\Equipment\Actions\IndexIotLogAction;
use Modules\Masterdata\Equipment\Actions\IndexStandardParameterAction;
use Modules\Masterdata\Equipment\Actions\ShowEquipmentAction;
use Modules\Masterdata\Equipment\Actions\ShowEquipmentCategoryAction;
use Modules\Masterdata\Equipment\Actions\ShowEquipmentErrorAction;
use Modules\Masterdata\Equipment\Actions\ShowEquipmentParameterAction;
use Modules\Masterdata\Equipment\Actions\ShowIotLogAction;
use Modules\Masterdata\Equipment\Actions\ShowStandardParameterAction;
use Modules\Masterdata\Equipment\Actions\StoreEquipmentAction;
use Modules\Masterdata\Equipment\Actions\StoreEquipmentCategoryAction;
use Modules\Masterdata\Equipment\Actions\StoreEquipmentErrorAction;
use Modules\Masterdata\Equipment\Actions\StoreEquipmentParameterAction;
use Modules\Masterdata\Equipment\Actions\StoreStandardParameterAction;
use Modules\Masterdata\Equipment\Actions\UpdateEquipmentAction;
use Modules\Masterdata\Equipment\Actions\UpdateEquipmentCategoryAction;
use Modules\Masterdata\Equipment\Actions\UpdateEquipmentErrorAction;
use Modules\Masterdata\Equipment\Actions\UpdateEquipmentParameterAction;
use Modules\Masterdata\Equipment\Actions\UpdateStandardParameterAction;

Route::group([], function (): void {
    Route::prefix('v1/equipment')->name('equipment.')->group(function (): void {
        Route::get('/', IndexEquipmentAction::class)->name('index');
        Route::post('/', StoreEquipmentAction::class)->name('store');
        Route::get('/{id}', ShowEquipmentAction::class)->name('show');
        Route::put('/{id}', UpdateEquipmentAction::class)->name('update');
        Route::delete('/{id}', DeleteEquipmentAction::class)
            ->middleware('block.if.referenced:' . Equipment::class)
            ->name('destroy');
    });

    Route::prefix('v1/equipment-parameters')->name('equipment-parameters.')->group(function (): void {
        Route::get('/', IndexEquipmentParameterAction::class)->name('index');
        Route::post('/', StoreEquipmentParameterAction::class)->name('store');
        Route::get('/{id}', ShowEquipmentParameterAction::class)->name('show');
        Route::put('/{id}', UpdateEquipmentParameterAction::class)->name('update');
        Route::delete('/{id}', DeleteEquipmentParameterAction::class)
            ->middleware('block.if.referenced:' . EquipmentParameter::class)
            ->name('destroy');
    });

    Route::prefix('v1/standard-parameters')->name('standard-parameters.')->group(function (): void {
        Route::get('/', IndexStandardParameterAction::class)->name('index');
        Route::post('/', StoreStandardParameterAction::class)->name('store');
        Route::get('/{id}', ShowStandardParameterAction::class)->name('show');
        Route::put('/{id}', UpdateStandardParameterAction::class)->name('update');
        Route::delete('/{id}', DeleteStandardParameterAction::class)
            ->middleware('block.if.referenced:' . StandardParameter::class)
            ->name('destroy');
    });

    Route::prefix('v1/equipment-errors')->name('equipment-errors.')->group(function (): void {
        Route::get('/', IndexEquipmentErrorAction::class)->name('index');
        Route::post('/', StoreEquipmentErrorAction::class)->name('store');
        Route::get('/{id}', ShowEquipmentErrorAction::class)->name('show');
        Route::put('/{id}', UpdateEquipmentErrorAction::class)->name('update');
        Route::delete('/{id}', DeleteEquipmentErrorAction::class)
            ->middleware('block.if.referenced:' . EquipmentError::class)
            ->name('destroy');
    });

    Route::prefix('v1/equipment-categories')->name('equipment-categories.')->group(function (): void {
        Route::get('/', IndexEquipmentCategoryAction::class)->name('index');
        Route::post('/', StoreEquipmentCategoryAction::class)->name('store');
        Route::get('/{id}', ShowEquipmentCategoryAction::class)->name('show');
        Route::put('/{id}', UpdateEquipmentCategoryAction::class)->name('update');
        Route::delete('/{id}', DeleteEquipmentCategoryAction::class)
            ->middleware('block.if.referenced:' . EquipmentCategory::class)
            ->name('destroy');
    });

    Route::prefix('v1/iot-logs')->name('iot-logs.')->group(function (): void {
        Route::get('/', IndexIotLogAction::class)->name('index');
        Route::get('/{id}', ShowIotLogAction::class)->name('show');
    });
});
