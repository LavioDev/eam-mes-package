<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenanceCategoryAction;
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
use Modules\Equipment\Maintenance\Actions\ShowMaintenancePlanAction;
use Modules\Equipment\Maintenance\Actions\ShowMaintenanceCategoryAction;
use Modules\Equipment\Maintenance\Actions\ShowMaintenanceItemAction;
use Modules\Equipment\Maintenance\Actions\UpdateMaintenanceItemAction;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenanceItemAction;
use Modules\Equipment\Maintenance\Actions\StoreMaintenanceScheduleAction;
use Modules\Equipment\Maintenance\Actions\ShowMaintenanceScheduleAction;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenanceScheduleAction;
use Modules\Equipment\Maintenance\Actions\IndexMaintenanceLogAction;
use Modules\Equipment\Maintenance\Actions\StoreMaintenanceLogAction;
use Modules\Equipment\Maintenance\Actions\ShowMaintenanceLogAction;
use Modules\Equipment\Maintenance\Actions\UpdateMaintenanceLogAction;
use Modules\Equipment\Maintenance\Actions\DeleteMaintenanceLogAction;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    // Maintenance Plans
    Route::get('maintenance-plans', IndexMaintenancePlanAction::class);
    Route::post('maintenance-plans', StoreMaintenancePlanAction::class);
    Route::get('maintenance-plans/{id}', ShowMaintenancePlanAction::class);
    Route::put('maintenance-plans/{id}', UpdateMaintenancePlanAction::class);
    Route::delete('maintenance-plans/{id}', DeleteMaintenancePlanAction::class);

    // Maintenance Schedules
    Route::get('maintenance-schedules', IndexMaintenanceScheduleAction::class);
    Route::post('maintenance-schedules', StoreMaintenanceScheduleAction::class);
    Route::get('maintenance-schedules/{id}', ShowMaintenanceScheduleAction::class);
    Route::put('maintenance-schedules/{id}', UpdateMaintenanceScheduleAction::class);
    Route::delete('maintenance-schedules/{id}', DeleteMaintenanceScheduleAction::class);

    // Maintenance Categories
    Route::get('maintenance-categories', IndexMaintenanceCategoryAction::class);
    Route::post('maintenance-categories', StoreMaintenanceCategoryAction::class);
    Route::get('maintenance-categories/{id}', ShowMaintenanceCategoryAction::class);
    Route::put('maintenance-categories/{id}', UpdateMaintenanceCategoryAction::class);
    Route::delete('maintenance-categories/{id}', DeleteMaintenanceCategoryAction::class);

    // Maintenance Items
    Route::get('maintenance-items', IndexMaintenanceItemAction::class);
    Route::post('maintenance-items', StoreMaintenanceItemAction::class);
    Route::get('maintenance-items/{id}', ShowMaintenanceItemAction::class);
    Route::put('maintenance-items/{id}', UpdateMaintenanceItemAction::class);
    Route::delete('maintenance-items/{id}', DeleteMaintenanceItemAction::class);

    // Maintenance Logs
    Route::get('maintenance-logs', IndexMaintenanceLogAction::class);
    Route::post('maintenance-logs', StoreMaintenanceLogAction::class);
    Route::get('maintenance-logs/{id}', ShowMaintenanceLogAction::class);
    Route::put('maintenance-logs/{id}', UpdateMaintenanceLogAction::class);
    Route::delete('maintenance-logs/{id}', DeleteMaintenanceLogAction::class);
});