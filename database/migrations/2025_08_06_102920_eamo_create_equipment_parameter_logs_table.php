<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eamo_equipment_parameter_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('equipment_id', 36);
            $table->string('equipment_parameter_id', 36);
            $table->string('product_id',36)->nullable();
            $table->string('lot_id',36)->nullable();
            $table->string('unit_id', 36)->nullable();
            $table->string('value', 36)->nullable();
            $table->string('component_id', 36)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eamo_equipment_parameter_logs');
    }
};
