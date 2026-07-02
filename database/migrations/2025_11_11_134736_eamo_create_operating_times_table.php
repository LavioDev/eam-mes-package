<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eamo_operating_times', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('equipment_id', 36);
            $table->string('equipment_name')->nullable();
            $table->decimal('working_time', 10, 4)->default(0);
            $table->decimal('planned_stop_time', 10, 4)->default(0);
            $table->decimal('unplanned_stop_time', 10, 4)->default(0);
            $table->decimal('planned_operating_time', 10, 4)->default(0);
            $table->decimal('actual_operating_time', 10, 4)->default(0);
            $table->decimal('availability_factor', 5, 2)->default(0);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eamo_operating_times');
    }
};
