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
        Schema::create('eamo_maintenance_plans', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('plan_code');
            $table->string("equipment_id", 36); //helps querying or grouping
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('actual_start_time')->nullable();
            $table->time('actual_end_time')->nullable();
            $table->date('date')->nullable();
            $table->string('cycle_type', 255)->nullable();
            $table->integer('cycle_interval')->nullable();
            $table->text('notes')->nullable();
            $table->string('maintenance_type', 255);
            $table->string('maintenance_category_id', 36)->nullable();
            $table->string('user_id', 36)->nullable();
            $table->timestamps();

            $table->foreign('maintenance_category_id')
                ->references('id')
                ->on('eamo_maintenance_categories')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eamo_maintenance_plans');
    }
};
