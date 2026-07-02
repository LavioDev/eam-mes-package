<?php

declare(strict_types=1);
use Modules\Equipment\Checklist\Actions\IndexChecklistDetailAction;
use Modules\Equipment\Checklist\Actions\StoreChecklistDetailAction;
use Modules\Equipment\Checklist\Actions\UpdateChecklistDetailAction;
use Modules\Equipment\Checklist\Actions\JudgeSessionAction;
use Illuminate\Support\Facades\Route;
use Modules\Equipment\Checklist\Actions\IndexChecklistSessionAction;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('checklist-details', IndexChecklistDetailAction::class);
    Route::post('checklist-details', StoreChecklistDetailAction::class);
    Route::put('checklist-details', UpdateChecklistDetailAction::class);

    Route::get('checklist-sessions', IndexChecklistSessionAction::class);

});
