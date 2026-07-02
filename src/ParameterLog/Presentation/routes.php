<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\DeleteEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\IndexEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\OverviewEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\SaveEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\ShowEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\StoreEquipmentParameterLogAction;
use Modules\Equipment\ParameterLog\Domain\Actions\Resources\UpdateEquipmentParameterLogAction;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::prefix('equipment/equipment-parameter/logs')->group(function () {
        Route::get('/', IndexEquipmentParameterLogAction::class)->name('equipment-parameter-logs.index');
        Route::post('/', StoreEquipmentParameterLogAction::class)->name('equipment-parameter-logs.store');
        Route::get('/{id}', ShowEquipmentParameterLogAction::class)->name('equipment-parameter-logs.show');
        Route::put('/{id}', UpdateEquipmentParameterLogAction::class)->name('equipment-parameter-logs.update');
        Route::delete('/{id}', DeleteEquipmentParameterLogAction::class)->name('equipment-parameter-logs.delete');

        Route::post('/save', SaveEquipmentParameterLogAction::class)->name('equipment-parameter-logs.save');

        Route::get('/overview/{id}', OverviewEquipmentParameterLogAction::class)->name('equipment-parameter-logs.overview');
    });
});
