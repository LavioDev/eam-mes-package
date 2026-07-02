<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenanceCategoryAction;
// use Modules\Equipment\Maintenance\Actions\IO\MaintenancePlanExport;
// use Modules\Equipment\Maintenance\Actions\IO\MaintenancePlanSampleExport;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenancePlanAction;
use Modules\Equipment\Maintenance\Actions\IndexMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Actions\IndexMaintenanceItemAction;
use Modules\Equipment\Maintenance\Actions\IndexMaintenancePlanAction;
use Modules\Equipment\Maintenance\Actions\IndexMaintenanceScheduleAction;
use Modules\Equipment\Maintenance\Actions\StoreMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Actions\StoreMaintenanceItemAction;
use Modules\Equipment\Maintenance\Actions\StoreMaintenancePlanAction;
use Modules\Equipment\Maintenance\Actions\UpdateMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Actions\UpdateMaintenancePlanAction;
use Modules\Equipment\Maintenance\Actions\UpdateMaintenanceScheduleAction;

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