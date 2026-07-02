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
        Schema::create('eamo_maintenance_categories', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('eamo_maintenance_plans', function (Blueprint $table) {
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
        Schema::table('eamo_maintenance_plans', function (Blueprint $table) {
            $table->dropForeign(['maintenance_category_id']);
        });

        Schema::dropIfExists('eamo_maintenance_categories');
    }
};
