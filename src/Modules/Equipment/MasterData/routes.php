<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\MasterData\Models\Equipment;
use Modules\Equipment\MasterData\Models\EquipmentCategory;
use Modules\Equipment\MasterData\Models\EquipmentError;
use Modules\Equipment\MasterData\Models\EquipmentParameter;
use Modules\Equipment\MasterData\Models\StandardParameter;
use Modules\Equipment\MasterData\Actions\DeleteEquipmentAction;
use Modules\Equipment\MasterData\Actions\DeleteEquipmentCategoryAction;
use Modules\Equipment\MasterData\Actions\DeleteEquipmentErrorAction;
use Modules\Equipment\MasterData\Actions\DeleteEquipmentParameterAction;
use Modules\Equipment\MasterData\Actions\DeleteStandardParameterAction;
use Modules\Equipment\MasterData\Actions\IndexEquipmentAction;
use Modules\Equipment\MasterData\Actions\IndexEquipmentCategoryAction;
use Modules\Equipment\MasterData\Actions\IndexEquipmentErrorAction;
use Modules\Equipment\MasterData\Actions\IndexEquipmentParameterAction;
use Modules\Equipment\MasterData\Actions\IndexIotLogAction;
use Modules\Equipment\MasterData\Actions\IndexStandardParameterAction;
use Modules\Equipment\MasterData\Actions\ShowEquipmentAction;
use Modules\Equipment\MasterData\Actions\ShowEquipmentCategoryAction;
use Modules\Equipment\MasterData\Actions\ShowEquipmentErrorAction;
use Modules\Equipment\MasterData\Actions\ShowEquipmentParameterAction;
use Modules\Equipment\MasterData\Actions\ShowIotLogAction;
use Modules\Equipment\MasterData\Actions\ShowStandardParameterAction;
use Modules\Equipment\MasterData\Actions\StoreEquipmentAction;
use Modules\Equipment\MasterData\Actions\StoreEquipmentCategoryAction;
use Modules\Equipment\MasterData\Actions\StoreEquipmentErrorAction;
use Modules\Equipment\MasterData\Actions\StoreEquipmentParameterAction;
use Modules\Equipment\MasterData\Actions\StoreStandardParameterAction;
use Modules\Equipment\MasterData\Actions\UpdateEquipmentAction;
use Modules\Equipment\MasterData\Actions\UpdateEquipmentCategoryAction;
use Modules\Equipment\MasterData\Actions\UpdateEquipmentErrorAction;
use Modules\Equipment\MasterData\Actions\UpdateEquipmentParameterAction;
use Modules\Equipment\MasterData\Actions\UpdateStandardParameterAction;

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

