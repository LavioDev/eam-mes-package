<?php

declare(strict_types=1);

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
        Schema::create('equipment_equipment_errors', function (Blueprint $table) {
            $table->string('equipment_id', 36);
            $table->string('equipment_error_id', 36);

            $table->primary(['equipment_id', 'equipment_error_id']);
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreign('equipment_error_id')->references('id')->on('equipment_errors')->onDelete('cascade')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_equipment_errors');
    }
};
