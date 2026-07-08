<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eamo_equipment_parameters', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('code', 32)->unique();
            $table->string('equipment_id', 36)->nullable();
            $table->string('unit_id', 36)->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('eamo_equipment')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eamo_equipment_parameters');
    }
};
