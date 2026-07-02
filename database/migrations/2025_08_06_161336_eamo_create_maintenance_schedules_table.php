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
        Schema::create('eamo_maintenance_schedules', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('equipment_id', 255);
            $table->string('maintenance_item_id', 36)->nullable();
            $table->string('maintenance_plan_id', 36);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eamo_maintenance_schedules');
    }
};
