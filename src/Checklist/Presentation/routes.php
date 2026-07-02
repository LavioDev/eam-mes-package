<?php

declare(strict_types=1);
use Modules\Equipment\Checklist\Domain\Actions\Resources\IndexChecklistDetailAction;
use Modules\Equipment\Checklist\Domain\Actions\Resources\StoreChecklistDetailAction;
use Modules\Equipment\Checklist\Domain\Actions\Resources\UpdateChecklistDetailAction;
use Modules\Equipment\Checklist\Domain\Actions\Resources\JudgeSessionAction;
use Illuminate\Support\Facades\Route;
use Modules\Equipment\Checklist\Domain\Actions\Resources\IndexChecklistSessionAction;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('checklist-details', IndexChecklistDetailAction::class);
    Route::post('checklist-details', StoreChecklistDetailAction::class);
    Route::put('checklist-details', UpdateChecklistDetailAction::class);

    Route::get('checklist-sessions', IndexChecklistSessionAction::class);

});
