<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eamo_equipment_error_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('equipment_id', 36);
            $table->string('equipment_error_id', 36)->nullable();
            $table->dateTime('occurred_at')->nullable(); // Thời điểm lỗi phát sinh
            $table->dateTime('restarted_at')->nullable();    // Thời điểm thiết bị chạy lại
            $table->dateTime('handled_at')->nullable();      // Thời điểm xử lý xong lỗi
            $table->string('handler_id')->nullable();
            $table->nullableTimestamps();

            $table->index(['equipment_id', 'occurred_at'], 'eamo_error_logs_equipment_id_occurred_at_idx');
            $table->index(
                ['equipment_id', 'equipment_error_id', 'restarted_at'],
                'eamo_error_logs_eq_id_error_id_restarted_at_idx'
            );

            $table->foreign('equipment_id')->references('id')->on('eamo_equipment')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('equipment_error_id')->references('id')->on('eamo_equipment_errors')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('handler_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eamo_equipment_error_logs');
    }
};
