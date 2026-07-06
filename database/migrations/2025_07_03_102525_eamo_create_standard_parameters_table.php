<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standard_parameters', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('equipment_id', 36)->nullable();
            $table->string('equipment_parameter_id', 36);
            $table->decimal('standard', 19, 4);
            $table->decimal('standard_max', 19, 4);
            $table->decimal('standard_min', 19, 4);
            $table->string('unit_id', 36)->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('equipment_parameter_id')->references('id')->on('equipment_parameters')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standard_parameters');
    }
};
