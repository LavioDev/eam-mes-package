<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eamo_maintenance_logs', function (Blueprint $table) {
            $table->string('id', length: 36)->primary();
            $table->string('maintenance_schedule_id', 36);
            $table->date('log_date');
            $table->string('note')->nullable();
            $table->string('result');
            $table->string('type', 36)->nullable();
            $table->timestamps();

            $table->foreign('maintenance_schedule_id')
                ->references('id')
                ->on('eamo_maintenance_schedules')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eamo_maintenance_logs');
    }
};
