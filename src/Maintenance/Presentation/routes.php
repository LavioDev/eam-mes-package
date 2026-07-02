<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\DeleteMaintenanceCategoryAction;
// use Modules\Equipment\Maintenance\Domain\Actions\IO\MaintenancePlanExport;
// use Modules\Equipment\Maintenance\Domain\Actions\IO\MaintenancePlanSampleExport;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\DeleteMaintenancePlanAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\IndexMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\IndexMaintenanceItemAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\IndexMaintenancePlanAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\IndexMaintenanceScheduleAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\StoreMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\StoreMaintenanceItemAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\StoreMaintenancePlanAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\UpdateMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\UpdateMaintenancePlanAction;
use Modules\Equipment\Maintenance\Domain\Actions\Resources\UpdateMaintenanceScheduleAction;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('maintenance-plans', IndexMaintenancePlanAction::class);
    Route::post('maintenance-plans', StoreMaintenancePlanAction::class);
    Route::put('maintenance-plans/{id}', UpdateMaintenancePlanAction::class);
    Route::delete('maintenance-plans/{id}', DeleteMaintenancePlanAction::class);

    Route::get('maintenance-schedules', IndexMaintenanceScheduleAction::class);
    Route::put('maintenance-schedules/{id}', UpdateMaintenanceScheduleAction::class);


    Route::get('maintenance-categories', IndexMaintenanceCategoryAction::class);
    Route::post('maintenance-categories', StoreMaintenanceCategoryAction::class);
    Route::put('maintenance-categories/{id}', UpdateMaintenanceCategoryAction::class);
    Route::delete('maintenance-categories/{id}', DeleteMaintenanceCategoryAction::class);

    Route::get('maintenance-items', IndexMaintenanceItemAction::class);
    Route::post('maintenance-items', StoreMaintenanceItemAction::class);
});